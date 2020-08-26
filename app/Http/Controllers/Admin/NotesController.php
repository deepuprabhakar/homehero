<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\WorkItem;
use App\ItemNote;
use Datatables;
use Form;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workItems = \App\WorkItem::with('itemType')->with('itemSubType')->get();

        $items = [];

        foreach ($workItems as $key => $item) { 
            
            $items[$item->id] = $item->itemType->type.
                                ' - ' .$item->itemSubType->sub_type.
                                ' - ' .$item->detail;
                                
        }
        
        return view('admin.notes.index', compact('items'));
    }

    /**
     * List all Steps
     * 
     * @param  Request $request 
     * 
     * @return Datatables
     */
    public function getNotes(Request $request)
    {
        $workItem = \App\WorkItem::find($request->input('item'));

        $notes = $workItem->notes;

        $datatables = Datatables::of($notes)
            
            ->addColumn('action', function ($notes) {
            
                return '<div class="text-center">
                            <a href="'.route('admin.item-notes.edit', $notes->id).'" class="datatable-action btn btn-primary btn-xs">
                                <i class="fa fa-pencil-square"></i></a>'.
                            
                            Form::open(['url' => route('admin.item-notes.destroy', $notes->id), 'method' => 'DELETE', 'class' => 'delete-form'])

                            .'<button type="submit" class="datatable-action delete btn btn-danger btn-xs" aria-label="Left Align">
                              <i class="fa fa-trash"></i></span>
                            </button>'.

                            Form::close()

                            .'</div>
                        ';
            });
            
        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $workItems = \App\WorkItem::with('itemType')->with('itemSubType')->get();

        $items = [];

        foreach ($workItems as $key => $item) { 
            
            $items[$item->id] = $item->itemType->type.
                                ' - ' .$item->itemSubType->sub_type.
                                ' - ' .$item->detail;
                                
        }

        return view('admin.notes.add', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'work_item_id.required' => 'The work item field is required.',
        ];
        $this->validate($request, [
                'work_item_id' => 'required',
                'note' => 'required',
            ], $messages);

        $note = ItemNote::create($request->all());

        if(!is_null($note))
        {
            $res['success'] = "New Note added!";
            return $res;
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
        return redirect()->route('admin.item-steps.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $note = ItemNote::find($id);
        if(is_null($note))
            return view('admin.404');
        else
        {
            $workItems = \App\WorkItem::with('itemType')->with('itemSubType')->get();

            $items = [];

            foreach ($workItems as $key => $item) { 
                
                $items[$item->id] = $item->itemType->type.
                                    ' - ' .$item->itemSubType->sub_type.
                                    ' - ' .$item->detail;
                                    
            }

            return view('admin.notes.edit', compact('note', 'items'));                                        
        }
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
        $messages = [
            'work_item_id.required' => 'The work item field is required.',
        ];

        $this->validate($request, [
                'work_item_id' => 'required',
                'note' => 'required',
            ], $messages);

        $note = ItemNote::find($id);
        
        $data = $note->update($request->all());
        
        if(!is_null($data))
        {
            $res['success'] = "Updated!";
            return $res;
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
        $note = ItemNote::find($id);
        if(is_null($note))
            return view('admin.404');
        else
        {
            ItemNote::destroy($note->id);
            return redirect()->route('admin.item-notes.index');
        }
    }
}
