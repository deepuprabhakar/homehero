<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Proposal;
use App\ProposalVersion;
use Datatables;
use Form;
use DB;
use PDF;
use Validator;
use Carbon\Carbon;
use Mail;
use Session;
use File;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.proposals.index');
    }

    /**
     * List all Admins
     * @param  Request $request 
     * @return Datatables
     */
    public function proposals(Request $request)
    {
        $proposals = Proposal::latest()->select([
                        DB::raw("CONCAT(proposals.first_name,' ',proposals.last_name) as firstname"),
                        'proposals.*'
                        ])->get();

        return Datatables::of($proposals)
            ->addColumn('details_url', function ($proposal) {
                return route('proposals.details', $proposal->id);
            })
            ->addColumn('view_url', function ($proposal) {
                return route('admin.proposals.show', $proposal->id);
            })
            ->editColumn('approved', function($proposal) {
                if($proposal->approved == "Yes")
                    return 'Approved';
                else
                    return 'Not Approved';
            })
            ->editColumn('address', function ($proposal) {
                $address = json_decode($proposal->address, true);
                $address = $address['address1'].' , '
                           .$address['address2'].' , '
                           .$address['city'].' , '
                           .$address['state'].' - '
                           .$address['zip']
                           ;
                return $address;
            })
            ->editColumn('job_address', function ($proposal) {
                $job_address = json_decode($proposal->job_address, true);
                $job_address = $job_address['jobAddress1'].' , '
                           .$job_address['jobAddress2'].' , '
                           .$job_address['jobCity'].' , '
                           .$job_address['jobState'].' - '
                           .$job_address['jobZip']
                           ;
                return $job_address;
            })
            ->editColumn('id', function ($proposal) {
                return $proposal->id;
            })
            ->addColumn('count', function ($proposal) {
                return $proposal->proposalEntries->count();
            })
            ->addColumn('approve_button', function ($proposal) {
                if($proposal->approved == "Yes")
                    return '<div class="text-center"><button type="button" class="btn btn-success btn-xs approve-button table-approve-button" disabled>
                                <i class="fa fa-check" aria-hidden="true"></i>
                                Approved
                            </button></div>';
                else
                {
                    return  Form::open(['route' => 'proposals.approve', 'class' => 'approval-form full-width'])
                            .Form::hidden('proposal_id', $proposal->id)
                            ."<div class='text-center'><button type='submit' class='btn btn-success btn-xs table-approve-button click-to-approve'>
                                <i class='fa fa-thumbs-o-up' aria-hidden='true'></i>
                                Approve
                            </button></div>"
                            .Form::close();
                }
            })
            ->addColumn('actions', function($proposal){
                return '<div class="text-center">
                            <a href="'.route('admin.proposals.edit', $proposal->id).'" class="datatable-action btn btn-primary btn-xs">
                                <i class="fa fa-pencil-square"></i></a>'.

                            '<a href="'.route('admin.proposals.show', $proposal->id).'" class="datatable-action btn btn-primary btn-xs">
                                <i class="fa fa-eye" style="margin-left: -1px;"></i></a>'.
                            
                            '</div>
                        ';
            })
            ->addColumn('versions', function($proposal) {
                $count = $proposal->versions->count();

                if($count > 0)
                    return '<div class="text-center">
                                <a href="'. route('proposals.versions', $proposal) .'" class="btn btn-info btn-xs" style="margin:auto">
                                    View ('. $count .')
                                </a>
                            </div>';
                else
                    return '<div class="text-center">None</div>';
            })
            ->addColumn('staff', function ($proposal) {

                $staff_name = [];
                
                foreach ($proposal->staff as $staff) {
                    /*$staff_name .= '<span class="label label-default">'.
                                    $staff->firstname.
                                    '</span>';*/
                    array_push($staff_name, $staff->firstname. ' ' . $staff->lastname);
                }

                $staff = implode($staff_name, ", ");
                
                if($staff == "")
                    return 'Details not available!';
                else
                    return $staff;
            })
            ->make(true);
        
    }

    public function details($id)
    {
        $entries = Proposal::find($id)->proposalEntries();
        
        $count = $entries->count();

        return Datatables::eloquent($entries)
        
                ->addColumn('type', function ($entry) {
                    return $entry->workItem->type;
                })

                ->addColumn('detail', function ($entry) {
                    return $entry->workItem->detail;
                })
        
                ->make(true);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $proposal = Proposal::with('staff')
                            ->with('client')
                            ->with('proposalEntries')
                            ->where('id', $id)->first();
        if(is_null($proposal))
            return view('admin.404');     

        $address = json_decode($proposal->address, true);
        $address = $address['address1'].' , '
                   .$address['address2'].' , '
                   .$address['city'].' , '
                   .$address['state'].' - '
                   .$address['zip']
                   ;

        $job_address = json_decode($proposal->job_address, true);
        $job_address = $job_address['jobAddress1'].' , '
                   .$job_address['jobAddress2'].' , '
                   .$job_address['jobCity'].' , '
                   .$job_address['jobState'].' - '
                   .$job_address['jobZip']
                   ;
        
        return view('admin.proposals.show', compact('proposal', 'address', 'job_address'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $proposal = Proposal::find($id);

        if(is_null($proposal))
            return view('admin.404');

        $address = json_decode($proposal->address, true);
        $address = $address['address1'].' , '
                   .$address['address2'].' , '
                   .$address['city'].' , '
                   .$address['state'].' - '
                   .$address['zip']
                   ;

        $job_address = json_decode($proposal->job_address, true);
        $job_address = $job_address['jobAddress1'].' , '
                   .$job_address['jobAddress2'].' , '
                   .$job_address['jobCity'].' , '
                   .$job_address['jobState'].' - '
                   .$job_address['jobZip']
                   ;                   

        $locations = \App\Location::select([
                        DB::raw("CONCAT(sub_type,' (', type, ')') as location, id")
                        ])->orderBy('location')->pluck('location', 'id')->toArray();

        return view('admin.proposals.edit', compact('proposal', 'locations', 'address', 'job_address'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        // dd($request->all());
        $proposal = Proposal::find($id);

        if(is_null($proposal))
            return view('admin.404');

        $validator = Validator::make($request->all(), [
            'tasks.*.location_id' => 'required',
            'tasks.*.room_id' => 'required',
            'tasks.*.type' => 'required',
            'tasks.*.sub_type' => 'required',
            'tasks.*.work_item_id' => 'required',
            // 'tasks.*.parts.*.part_id' => 'required',
            // 'tasks.*.parts.*.price' => 'required',
            // 'tasks.*.extra_parts.*.part' => 'required',
            // 'tasks.*.extra_parts.*.price' => 'required',
            // 'tasks.*.steps' => 'required',
            // 'tasks.*.extra_steps.*.step' => 'required',
            // 'tasks.*.list_price' => 'required',
            // 'tasks.*.extended_price' => 'required',
            // 'tasks.*.notes' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('admin.proposals.edit', $proposal->id)
                        ->withErrors($validator)
                        ->withInput();
                        
        }

        try {

            $result = DB::transaction(function() use($request, $proposal) {

                if($request->has('approved'))
                    $proposal->approved = "Yes";
                else
                    $proposal->approved = "No";

                $proposal->updated_at = Carbon::now();
                $proposal->save();

                $tasks = $request->get('tasks');

                foreach ($tasks as $key => $task) {

                    $entry = $proposal->proposalEntries()->find($key);

                    // update proposal entry table
                    $entry->update($task);

                    $flag_parts = [];

                    if(array_key_exists('parts', $task))
                    {
                        // Check for duplicated entries to avoid foreign key exceptions
                        $foundIds = array();
                        $partIds = [];
                        foreach ( $task['parts'] as $index => $part )
                        {
                            if(array_key_exists('part_id', $part))
                            {
                                if(in_array($part['part_id'], $partIds))
                                    $foundIds[] = $index;
                                else
                                    $partIds[] = $part['part_id'];
                            }
                            
                        }
                        
                        foreach ($foundIds as $key => $value) {
                            unset($task['parts'][$value]);
                        }
                    }
                        
                    // dd($task['parts']);

                    if(!array_key_exists('parts', $task))
                    {
                        $task['parts'] = [];
                        $entry->parts()->sync($task['parts']);
                    }
                    else
                    {
                        foreach ($task['parts'] as $key => $part) {
                            if(array_key_exists('part_id', $part))
                            {
                                if($part['part_id'] != "")
                                    $flag_parts[$part['part_id']] = $part;
                            }
                            
                        }
                        
                        $entry->parts()->sync($flag_parts);
                    }

                    // sync part_proposal_entry table
                    // $entry->parts()->sync($task['parts']);

                    // sync extra_parts table
                    $extra_parts = $entry->extraParts()->pluck('id')->toArray();
                    // dd($task);
                    if(array_key_exists('extra_parts', $task))
                    {
                        if(count($task['extra_parts']) > 0)
                        {
                            foreach ($task['extra_parts'] as $key => $part) {
                                
                                $remove[] = $part['id'];
                                
                                if(in_array($part['id'], $extra_parts))
                                {
                                    if($part['part'] != "" || ctype_space($part['part']))
                                    {
                                        $extra_part = $entry->extraParts()->find($part['id']);
                                        $extra_part->update($part);
                                    }
                                }
                                else
                                {
                                    if($part['part'] != "" || ctype_space($part['part']))
                                        $entry->extraParts()->create($part);
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

                    // manage steps 
                    $extra_steps = $entry->extraSteps()->delete();
                    if(!array_key_exists('steps_new', $task))
                    {    
                        $task['steps_new'] = [];
                        // sync steps
                        $entry->steps()->sync($task['steps_new']);
                    }
                    else
                    {
                        $steps_to_save = [];
                        $extra_steps_to_save = [];
                        
                        $index = 0;
                        
                        foreach ($task['steps_new'] as $key => $new_step) {
                            $index++;
                            if($new_step['type'] == 'step')
                            {
                                $steps_to_save[$new_step['item_step_id']]['step_order'] = $index; 
                                $steps_to_save[$new_step['item_step_id']]['type'] = 'step'; 
                                // $steps_to_save[$new_step['item_step_id']]['item_step_id'] = $new_step['item_step_id']; 
                            }
                            else if($new_step['type'] == 'extra_step')
                            {
                                // $extra_steps = $entry->extraSteps()->pluck('id')->toArray();
                                

                                /*$remove_step[] = $new_step['item_step_id'];

                                if(in_array($new_step['item_step_id'], $extra_steps))
                                {
                                    $extra_step = $entry->extraSteps()->find($new_step['item_step_id']);
                                    $extra_step->step_order = $index;
                                    $extra_step->step = $new_step['step'];
                                    $extra_step->step_desc = $new_step['step_desc'];
                                    $extra_step->save();
                                }
                                else
                                {*/
                                    $new_extra_step['step'] = $new_step['step'];
                                    $new_extra_step['step_order'] = $index;
                                    $new_extra_step['type'] = 'extra_step';
                                    $new_extra_step['step_desc'] = $new_step['step_desc'];
                                    // dd($new_extra_step);
                                    $entry->extraSteps()->create($new_extra_step);
                                // }
                            }
                        }
                        $entry->steps()->sync($steps_to_save);
                        /*dd($steps_to_save);
                        dd(1);*/
                    }

                    // sync extra_steps table
                    /*$extra_steps = $entry->extraSteps()->pluck('id')->toArray();

                    if(array_key_exists('extra_steps', $task))
                    {
                        if(count($task['extra_steps']) > 0)
                        {   
                            foreach ($task['extra_steps'] as $key => $step) {
                                
                                $remove_step[] = $step['id'];
                                
                                if(in_array($step['id'], $extra_steps))
                                {
                                    $extra_step = $entry->extraSteps()->find($step['id']);
                                    $extra_step->update($step);
                                }
                                else
                                {
                                    if($step['step'] != "" || ctype_space($step['step']))
                                        $entry->extraSteps()->create($step);
                                }
                            }

                            $delete = \App\ExtraStep::destroy(array_diff($extra_steps, $remove_step));
                        }
                        else
                        {
                            $delete = \App\ExtraStep::destroy($extra_steps);
                        }
                    }
                    else
                        $delete = \App\ExtraStep::destroy($extra_steps);*/

                }

                return $proposal;

            });

            if($result)
            {
                $filename = $proposal->first_name.'-Estimate-'. Carbon::now()->timestamp .'.pdf';

                $address = json_decode($proposal->address, true);
                $address = $address['address1'].' , '
                           .$address['address2'].' , '
                           .$address['city'].' , '
                           .$address['state'].' - '
                           .$address['zip']
                           ;

                $pdf = PDF::loadView('admin.pdf.proposal', compact('proposal', 'address'))
                            ->setPaper('letter', 'portrait')
                            ->save('public/proposals/'. $filename);

                $proposal_version = $proposal->versions()->create([
                        'title'     => 'Proposal Edited',
                        'remarks'   => 'Proposal Updates',
                        'file'      => $filename,
                        'approved'  => $proposal->approved,
                    ]);

                Session::flash('updated', 'Proposal updated successfully.');

                return redirect()->route('admin.proposals.edit', $proposal->id);
            }

        } catch (\Exception $e) {

            return $e->getMessage() . ' on ' . $e->getLine();
            
        }

        return redirect()->route('admin.proposals.edit', $proposal->id);
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
     * Generate PDF
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function download($id)
    {
        $proposal = Proposal::with('staff')
                            ->with('client')
                            ->with('proposalEntries')
                            ->where('id', $id)->first();

                            // return view('admin.pdf.proposal', compact('proposal'));

        $pdf = PDF::loadView('admin.pdf.proposal', compact('proposal'))->setPaper('letter', 'portrait');


        return $pdf->download('proposal-'. $proposal->first_name.'-'.$proposal->created_at->toDateString() .'.pdf');
    }

    /**
     * Approve proposal on request
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function approve(Request $request)
    {
        $proposal = Proposal::find($request->get('proposal_id'));

        $proposal->approved = "Yes";

        $proposal->save();

        $address = json_decode($proposal->address, true);
        $address = $address['address1'].' , '
                   .$address['address2'].' , '
                   .$address['city'].' , '
                   .$address['state'].' - '
                   .$address['zip']
                   ;

        $filename = $proposal->first_name.'-Estimate-'. Carbon::now()->timestamp .'.pdf';

        $pdf = PDF::loadView('admin.pdf.proposal', compact('proposal', 'address'))
                    ->save('public/proposals/'. $filename);

        $proposal_version = $proposal->versions()->create([
                'title'     => 'New Proposal Version',
                'remarks'   => 'Remarks',
                'file'      => $filename,
                'approved'   => $proposal->approved,
            ]);
        
        $response['success'] = 'Proposal approved!';

        return $response;
    }

    /**
     * get parts for proposal edit
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getParts(Request $request)
    {
        /*$item = \App\WorkItem::find($request->get('item'));

        if(!is_null($item))
            $res['res'] = $item->parts()->whereNotIn('id', $request->get('selected'))->pluck('part', 'id');
        else
            $res['res'] = null;*/
        if($request->has('selected'))
            $res['res'] = \App\Part::whereNotIn('id', $request->get('selected'))
                                   ->orderBy('part')
                                   ->pluck('id', 'part');
        else
            $res['res'] = \App\Part::orderBy('part')->pluck('id', 'part');

        return $res; 
    }

    public function getItems(Request $request)
    {

        $type = $request->get('type');
        $sub_type = $request->get('sub_type');

        return \App\WorkItem::where('type_id', $type)
                            ->where('sub_type_id', $sub_type)
                            ->get()
                            ->toArray();
    }

    public function getParts2(Request $request)
    {
        // $item = $request->get('item');
        
        // return \App\WorkItem::find($item)->parts;
        return \App\Part::orderBy('part')->get();
    }

    public function getSteps(Request $request)
    {
        $item = $request->get('item');
        
        return \App\WorkItem::find($item)->steps;
    }

    public function versions($id)
    {
        $proposal = Proposal::find($id);

        if(is_null($proposal))
            return view('admin.404');

        return view('admin.proposals.versions', compact('proposal'));
    }

    public function versionsList($id)
    {
        $versions = Proposal::findOrFail($id)->versions()->latest()->get();

        return Datatables::of($versions)

        ->editColumn('proposal_id', function($version) {
            return '<a href="'. route('admin.proposals.show', $version->proposal_id) .'">'.
                    '#'. $version->proposal_id . '</a>';
        })

        ->editColumn('file', function($version) {
            return '<div class="text-center">
                        <a class="btn btn-default btn-xs" 
                            href="'. asset('public/proposals/'. $version->file) .'"
                            target="_blank">
                            <i class="fa fa-download"></i> Download
                        </a>
                    </div>';
        })

        ->editColumn('approved', function($version) {
            if($version->approved == "Yes")
            {   
                if($version->sent == "Yes")
                    $sent = '<a class="btn btn-success btn-xs send-proposal"
                                data-toggle="tooltip" data-placement="top" title="Approved" 
                                style="width: 60px;"
                                id="'. $version->id .'" 
                                href="#send-proposal"
                                ><i class="fa fa-check"></i> Sent
                            </a>';
                else
                    $sent = '<a class="btn btn-success btn-xs send-proposal"
                                data-toggle="tooltip" data-placement="top" title="Approved" 
                                style="width: 60px;"
                                id="'. $version->id .'" 
                                href="#send-proposal"
                                ><i class="fa fa-paper-plane-o"></i> Send
                            </a>';
            }
            else
                $sent = 'Not approved';

            return $sent; 
        })

        ->make(true);
    }

    public function send(Request $request)
    {
        $response['status'] = false;
        $response['status_code'] = 403;
        $response['status_message'] = "";

        try {
            $version = ProposalVersion::findOrFail($request->get('version'));

            if($version)
            {
                $proposal = Proposal::findOrFail($version->proposal_id);

                $address = json_decode($proposal->address, true);
                $address = $address['address1'].' , '
                           .$address['address2'].' , '
                           .$address['city'].' , '
                           .$address['state'].' - '
                           .$address['zip']
                           ;

                $pathToFile = asset('public/proposals/'. $version->file);
                // mail login details to given email
                $mail = Mail::send('admin.emails.proposal', 
                        ['proposal' => $proposal, 'address' => $address], function ($message) use ($version, $pathToFile, $proposal) {
                    
                    $message->subject('Home Hero Proposal');
                    
                    $message->to($proposal->client->email);
                    
                    $message->attach($pathToFile);
                });
            }

            if($mail)
            {
                $version->sent = "Yes";
                $version->save();
                $response['status'] = true;
                $response['status_code'] = 200;
                $response['status_message'] = "success";
            }
            
        } catch (\Exception $e) {
            $response['status'] = false;
            $response['status_code'] = 500;
            $response['status_message'] = 'Someting went wrong. Please try later.' . $e->getMessage() . ' on ' . $e->getLine();

        } finally {
            return response()->json($response);
        }
        
    }

    /**
     * Add new task for proposal edit
     * @param Request $request [description]
     */
    public function addTask(Request $request)
    {
        $this->validate($request, 
            [
                'task_location'     =>  'required',
                'task_room'         =>  'required',
                'task_type'         =>  'required',
                'task_sub_type'     =>  'required',
                'task_work_item'    =>  'required',
                'task_list_price'   =>  'required|numeric',
                // 'task_extended_price'     =>  'required|numeric',
                // 'task_note'         =>  'required',
            ]);

        try {

            $input = $request->all();

            $proposal = Proposal::findOrFail($input['proposal_id']);

            $entry = $proposal->proposalEntries()->create([
                    'location_id' => $input['task_location'],
                    'room_id' => $input['task_room'],
                    'work_item_id' => $input['task_work_item'],
                    'notes' => $input['task_note'],
                    'list_price' => $input['task_list_price'],
                    'extended_price' => $input['task_extended_price'],
                    'quantity' => 1,
                ]);

            $proposal->updated_at = Carbon::now();
            $proposal->save();

            if($entry)
            {
                $item = \App\WorkItem::findOrFail($entry->work_item_id);
                
                if($item)
                {
                    // Add associated parts to proposal
                    if(count($item->parts))
                    {
                        foreach ($item->parts as $key => $part) {
                            $entry->parts()->attach($part->id, ['price' => $part->price]);
                        }
                    }

                    if(count($item->steps))
                    {
                        foreach ($item->steps as $key => $step) {
                            $entry->steps()->attach($step->id, ['step_order' => $key+1]);
                        }
                    }
                }
                
            }

            $res['success'] = 'Task added!';
            $res['status'] = true;

            return $res;
            
        } catch (\Exception $e) {
            
            $res['error'] = 'Someting went wrong. ' . $e->getMessage() . ' on ' . $e->getLine();
            $res['status'] = false;

        }

    }

    /**
     * Remove task
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function removeTask($id)
    {
        $task = \App\ProposalEntry::find($id);

        if($task)
        {
            if($task->proposal->proposalEntries->count() > 1)
            {

                $proposal = $task->proposal_id;
                if( count($task->media) > 0 )
                    foreach ($task->media as $file) {
                        File::delete('public/uploads/' . $file->media);    
                    }

                $task->delete();

                return redirect()->route('admin.proposals.edit', $proposal);
            }
            else
            {
                Session::flash('task_error', 'You cannot delete all tasks from a proposal!');
                return redirect()->back();
            }
            
        }
        else
            return view('admin.404');
    }
}
