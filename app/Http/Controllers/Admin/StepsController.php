<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\ItemStep;
use Datatables;
use Form;

class StepsController extends Controller
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
        
        return view('admin.steps.index', compact('items'));
    }

    /**
     * List all Steps
     *
     * @param  Request $request
     *
     * @return Datatables
     */
    public function getSteps(Request $request)
    {
        $workItem = \App\WorkItem::find($request->input('item'));

        if(!is_null($workItem))
        {
              $steps = $workItem->steps;

              $datatables = Datatables::of($steps)

                  ->addColumn('action', function ($steps) {

                      return '<div class="text-center">
                                  <a href="'.route('admin.item-steps.edit', $steps->id).'" class="datatable-action btn btn-primary btn-xs">
                                      <i class="fa fa-pencil-square"></i></a>'.

                                  Form::open(['url' => route('admin.item-steps.destroy', $steps->id), 'method' => 'DELETE', 'class' => 'delete-form'])

                                  .'<button type="submit" class="datatable-action delete btn btn-danger btn-xs" aria-label="Left Align">
                                    <i class="fa fa-trash"></i></span>
                                  </button>'.

                                  Form::close()

                                  .'</div>
                              ';
                  });

              return $datatables->make(true);
        }

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

        return view('admin.steps.add', compact('items'));
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
            'item_id.required' => 'The work item field is required.',
            'detail.required' => 'The step field in required.'
        ];
        $this->validate($request, [
                'item_id' => 'required',
                'detail' => 'required',
            ], $messages);

        $step = ItemStep::create($request->all());

        $step->step_id = 'Step'. str_pad($step->id, 3, '0', STR_PAD_LEFT);

        $step->save();

        if(!is_null($step))
        {
            $res['success'] = "New Step added!";
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
        $step = ItemStep::find($id);
        if(is_null($step))
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

            return view('admin.steps.edit', compact('step', 'items'));
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
            'item_id.required' => 'The work item field is required.',
            'detail.required' => 'The step field in required.'
        ];

        $this->validate($request, [
                'item_id' => 'required',
                'detail' => 'required',
            ], $messages);

        $step = ItemStep::find($id);

        $data = $step->update($request->all());

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
        $step = ItemStep::find($id);
        if(is_null($step))
            return view('admin.404');
        else
        {
            ItemStep::destroy($step->id);
            return redirect()->route('admin.item-steps.index');
        }
    }
}
