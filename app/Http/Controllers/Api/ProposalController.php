<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Proposal;
use App\WorkItem;
use App\ExtraPart;
use App\ProposalMedia;
use Image;
use File;
use DB;
use Mail;
use Config;
use Carbon\Carbon;

class ProposalController extends Controller
{
    public function __construct()
    {
        Config::set('auth.providers.users.model', \App\Staff::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staff = Auth::user();
        $proposals = [];
        if(count($staff->proposals))
        {
            $proposalIds = $staff->proposals()->pluck('id')->toArray();
                       
            $proposals = Proposal::with([
                    'proposalEntries' => function ($query) {
                        $query->sum('list_price');
                    },
                    'proposalEntries.parts' => function ($query) {
                        $query->sum('parts.price');
                    },
                    'proposalEntries.extraParts' => function ($query) {
                        $query->select('*');
                    },
                    'proposalEntries.steps' => function ($query) {
                        $query->select('*');
                    },
                    'proposalEntries.extraSteps' => function ($query) {
                        $query->select('*');
                    },
                    'proposalEntries.media' => function ($query) {
                        $query->select('*');
                    },
                    'proposalEntries.location' => function ($query) {
                        $query->select('*');
                    },
                    'proposalEntries.room' => function ($query) {
                        $query->select('*');
                    },
                    'proposalEntries.workItem' => function ($query) {
                        $query->select('*');
                    }
                ])
                ->with('staff')
                ->with('client')
                ->whereIn('id', $proposalIds)
                ->get();
        }
        
        return $proposals;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make(json_decode($request->input('json_data'), true), [

            'first_name'    => 'required',
            'last_name'     => 'required',
            'phone'         => 'required',
            'address'       => 'required',

            ]);

        if ($validator->fails()) {

            return response()->json([
                'errors' => $validator->errors(),
                'status' => false,
                ], 200);

        }

        try {

            $result = DB::transaction(function() use($request) {

                $input = json_decode($request->input('json_data'), true);

                // Insert into proposals
                $proposal = Proposal::create($input);

                $proposal->staff()->attach(Auth::user()->id); 
                // $proposal->staff()->attach(6);

                $media = [];

                foreach ($input['proposal_entries'] as $entry) {

                    $workItem = WorkItem::find($entry['work_item_id']);

                    $input['list_price']    = $workItem->price;
                    $input['location_id']   = $entry['location_id'];
                    $input['room_id']       = $entry['room_id'];
                    $input['work_item_id']  = $entry['work_item_id'];
                    $input['notes']         = $entry['notes'];
                    $input['task_local_id'] = $entry['task_local_id'];

                    if($entry['extended_price'] == "")
                        $input['extended_price'] = $workItem->price;
                    else
                        $input['extended_price'] = $entry['extended_price'];

                    // $input['quantity'] = 1;

                    // insert into proposal entries
                    $new_entry = $proposal->proposalEntries()->create($input);

                    //insert into part_proposal_entry
                    if(count($entry['parts']) != 0)

                        $new_entry->parts()->attach($entry['parts']);

                    // Insert extra parts into database
                    if(count($entry['extra_parts']) != 0)

                        foreach ($entry['extra_parts'] as $part) {

                            $new_entry->extraParts()->create($part);

                        }

                        $flag_parts = [];

                        //insert into item_step_proposal_entry
                        if(count($entry['steps']) != 0)
                        {
                            foreach ($entry['steps'] as $key => $step) {
                                if($step['item_step_id'] != "")
                                    $flag_parts[$step['item_step_id']] = $step;
                            }

                            $new_entry->steps()->sync($flag_parts);

                        }

                        // Insert extra steps into database
                        if(count($entry['extra_steps']) != 0)

                            foreach ($entry['extra_steps'] as $step) {

                                if($step)

                                    $new_entry->extraSteps()->create($step);

                            }

                            // insert into proposal media
                            if(count($entry['task_images'] > 0))
                            {
                                $media['type'] = 'image';

                                foreach ($entry['task_images'] as $image)
                                {
                                    $media['media'] = $image['image_name'];

                                    $new_entry->media()->create($media);
                                }
                            }
                    }

                // mail notification to admins
                $admins = \App\Admin::where('admin', 1)->pluck('email')->toArray();        
                
                Mail::send('admin.emails.new-proposal', ['data' => $proposal], function ($message) use ($admins){
                    
                    $message->subject('New Proposal');
                    
                    // $message->to(Config::get('app.app_mail'));
                    $message->to($admins);
                });

                return response()->json([
                    'error'     => 0,
                    'status'    => true,
                    'message'   => 'proposal added',
                    'proposal'  => $proposal
                    ]);

            });

            return $result;
            
        } catch (\Exception $e) {
            
            return response()->json([
                'error'     => 1,
                'status'    => false,
                'message'   => 'Someting went wrong. Please try later.' . $e->getMessage() . ' on ' . $e->getLine(),
                'proposal'  => null
                ]);
            
        }

        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $proposal = Proposal::with('proposalEntries')->with('staff')->where('id', $id)->first();

        if(is_null($proposal))
            return response()->json([
                'error'     => 'proposal_not_found',
                'status'    => false,
                'message'   => 'proposal not found!',
                'proposal'  => 0
                ]);

        return response()->json([
            'error'     => 0,
            'status'    => true,
            'message'   => 'proposal found',
            'proposal'  => $proposal
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $proposal = Proposal::with('proposalEntries')->with('staff')->where('id', $id)->first();

        if(is_null($proposal))
            return response()->json([
                'error'     => 'proposal_not_found',
                'status'    => false,
                'message'   => 'proposal not found!',
                'proposal'  => 0
                ]);

        return response()->json([
            'error'     => 0,
            'status'    => true,
            'message'   => 'proposal found',
            'proposal'  => $proposal
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = json_decode($request->get('json_data'), true);
        
        $proposal = Proposal::find($input['proposal_id']);

        if(is_null($proposal))
            return response()->json([
                'error'     => 'proposal_not_found',
                'status'    => false,
                'message'   => 'proposal not found!',
                'proposal'  => 0
                ]);

        try {

                $result = DB::transaction(function() use($request, $proposal, $input) {

                $proposal->job_address = $input['job_address'];
                $proposal->grand_total = $input['grand_total'];
                $proposal->discount = $input['discount'];
                $proposal->net_total = $input['net_total'];
                $proposal->updated_at = Carbon::now();
                $proposal->save();

                // sync staff
                $staff = $proposal->staff()->pluck('staff_id')->toArray();

                array_push($staff, Auth::user()->id);
                // array_push($staff, 1);

                $proposal->staff()->sync($staff);

                // get existing proposal entriles
                $existing_proposal_entry = $proposal->proposalEntries()->pluck('id')->toArray();

                $proposal_entry_id = [];

                foreach ($input['proposal_entries'] as $entry) {

                    if(array_key_exists('task_id', $entry))
                    {
                        $proposal_entry = $proposal->proposalEntries()->findOrFail($entry['task_id']);
                        $proposal_entry_id[] = $entry['task_id'];
                    }
                    else
                    {
                        $proposal_entry = new \App\ProposalEntry();

                    }

                    $workItem = WorkItem::findOrFail($entry['work_item_id']);

                    $proposal_entry->proposal_id    = $proposal->id;
                    $proposal_entry->list_price     = $workItem->price;
                    $proposal_entry->location_id    = $entry['location_id'];
                    $proposal_entry->room_id        = $entry['room_id'];
                    $proposal_entry->work_item_id   = $workItem->id;
                    $proposal_entry->notes          = $entry['notes'];
                    $proposal_entry->quantity       = 1;
                    $proposal_entry->task_local_id  = $entry['task_local_id'];

                    if($entry['extended_price'] == "")
                        $proposal_entry->extended_price = $workItem->price;
                    else
                        $proposal_entry->extended_price = $entry['extended_price'];

                    $proposal_entry->save();

                    //insert into part_proposal_entry
                    if(count($entry['parts']) != 0)

                        $proposal_entry->parts()->sync($entry['parts']);
                    else
                        $proposal_entry->parts()->sync([]);

                    // Insert extra parts into database
                    $extra_parts = $proposal_entry->extraParts()->pluck('id')->toArray();

                    if(array_key_exists('extra_parts', $entry))
                    {
                        if(count($entry['extra_parts']) > 0)
                        {
                            foreach ($entry['extra_parts'] as $key => $part) 
                            {
                                
                                $remove[] = $part['part_id'];
                                
                                if(in_array($part['part_id'], $extra_parts))
                                {
                                    $extra_part = $proposal_entry->extraParts()->findOrFail($part['part_id']);
                                    $extra_part->part = $part['part'];
                                    $extra_part->price = $part['price'];
                                    $extra_part->part_desc = $part['part_desc'];
                                    $extra_part->quantity = $part['quantity'];
                                    $extra_part->save();
                                }
                                else
                                {
                                    if($part['part'] != "" || ctype_space($part['part']))
                                        $proposal_entry->extraParts()->create([

                                                'part'  => $part['part'],
                                                'price' => $part['price'],
                                                'part_desc' => $part['part_desc'],
                                                'quantity' => $part['quantity'],

                                            ]);
                                }
                            }

                            $delete = \App\ExtraPart::destroy(array_diff($extra_parts, $remove));
                        }
                        else
                        {
                            $delete = \App\ExtraPart::destroy($extra_parts);
                        }
                    }
                    else
                        $delete = \App\ExtraPart::destroy($extra_parts);

                    //insert into item_step_proposal_entry
                    if(count($entry['steps']) != 0)
                    {
                        $new_steps = [];

                        foreach ($entry['steps'] as $step) {
                            $new_steps[$step['item_step_id']] = $step;    
                        }

                        $proposal_entry->steps()->sync($new_steps);

                    }
                    else
                    {
                        $proposal_entry->steps()->sync([]);
                    }

                    // Insert extra steps into database
                    if(count($entry['extra_steps']) != 0)
                    {
                        $proposal_entry->extraSteps()->delete();

                        foreach ($entry['extra_steps'] as $step) 
                        {

                            $proposal_entry->extraSteps()->create($step);

                        }
                    }
                    else
                        $proposal_entry->extraSteps()->delete();

                    // insert into proposal media
                    if(count($entry['task_images']) > 0)
                    {
                        $media['type'] = 'image';

                        foreach ($proposal_entry->media as $file) {
                            File::delete('public/uploads/' . $file->media);
                        }

                        $proposal_entry->media()->delete();

                        foreach ($entry['task_images'] as $image)
                        {
                            $media['media'] = $image['image_name'];

                            $proposal_entry->media()->create($media);
                        }
                    }
                    else if(count($entry['task_images']) == 0)
                    {
                        foreach ($proposal_entry->media as $file) {
                            File::delete('public/uploads/' . $file->media);
                        }

                        $proposal_entry->media()->delete();
                    }

                }

                $proposal_entry_delete = array_diff($existing_proposal_entry, $proposal_entry_id);

                // Delete exceptional proposal entries
                $deleted = \App\ProposalEntry::destroy($proposal_entry_delete);

                return $proposal;

            });

            return response()->json([
                'error'     => 0,
                'status'    => true,
                'message'   => 'proposal updated',
                'proposal'  => $result
                ]);
            
        } catch (\Exception $e) {
                return response()->json([
                    'error'     => 1,
                    'status'    => false,
                    'message'   => 'Someting went wrong. Please try later.' . $e->getMessage() . ' on ' . $e->getLine(),
                    'proposal'  => null
                    ]);
        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Upload all proposal media
     * @return [type] [description]
     */
    public function uploadMedia(Request $request)
    {
        $input = json_decode($request->input('json_data'), true);

        $filepath = public_path('uploads');

        $flag = 0; $uploaded_files = [];

        foreach ($input['image_names'] as $filename) {

            $media = ProposalMedia::where('media', $filename)->first();

            // $image = new UploadedFiles();

            if(!is_null($media))
            {
                $media->flag = 1;

                $media->save();

                // $image->image_name = $filename;

                // $uploaded_files[] = $image;

                $uploaded_files[] = $filename;
            }

            else
            {
                File::delete($filepath .'/'. $filename);

                return response()->json([
                    'error'     => 1,
                    'status'    => false,
                    'message'   => 'upload failed - filename mismatch',
                    ]);
            }

            $flag = 1;

        }

        if($flag == 0)
            return response()->json([
                'error'     => 1,
                'status'    => false,
                'message'   => 'upload failed',
                'uploaded_files' => $uploaded_files
                ]);
        else
            return response()->json([
                'error'     => 0,
                'status'    => true,
                'message'   => 'upload success',
                'uploaded_files' => $uploaded_files
                ]);
    }

    
}

/*class UploadedFiles {
    public $image_name;
}*/
