<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\WorkItem;
use App\ItemStep;
use Datatables;
use Form;

class WorkItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.work-item.index');
    }

    /**
     * List all Work Items
     *
     * @param  Request $request
     *
     * @return Datatables
     */
    public function getWorkItems(Request $request)
    {
        $workItems = WorkItem::orderBy('detail')->get();

        $datatables = Datatables::of($workItems)

            ->addColumn('action', function ($workItems) {

                return '<div class="text-center">
                            <a href="'.route('admin.work-items.edit', $workItems->id).'" class="datatable-action btn btn-primary btn-xs">
                                <i class="fa fa-pencil-square"></i></a>'.

                            Form::open(['url' => route('admin.work-items.destroy', $workItems->id), 'method' => 'DELETE', 'class' => 'delete-form'])

                            .'<button type="submit" class="datatable-action delete btn btn-danger btn-xs" aria-label="Left Align">
                              <i class="fa fa-trash"></i></span>
                            </button>'.

                            Form::close()

                            .'</div>
                        ';
            })
            ->editColumn('detail', function ($workItems) {
                return '<div>' . str_limit($workItems->detail, 30) . '</div>';
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
        $parts = \App\Part::orderBy('part')->pluck('part', 'id')->toArray();
        $types = \App\Type::orderBy('type')->pluck('type', 'id')->toArray();
        $sub_types = \App\SubType::orderBy('sub_type')->pluck('sub_type', 'id')->toArray();
        return view('admin.work-item.add', compact('parts', 'types', 'sub_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->get('steps');
        $messages = [
            'sub_type_id' => 'The sub type field is required',
            'type_id' => 'The type field is required',
            'est_hrs.required' => 'The estimated hours field is required',
            'est_hrs.numeric' => 'The estimated hours must be a number.'
        ];

        $this->validate($request, [
                'type_id' => 'required|string',
                'sub_type_id' => 'required|string',
                'detail' => 'required|string|max:500',
                'price' => 'required|numeric',
                'est_hrs' => 'required|numeric',
                // 'parts' => 'required',
            ], $messages);

        $workItem = WorkItem::create($request->all());

        $workItem->parts()->attach($request->input('parts'));

        $workItem->item_id = 'Item'. str_pad($workItem->id, 3, '0', STR_PAD_LEFT);

        $workItem->save();

        if($request->has('steps'))
        {

            foreach ($request->get('steps') as $key => $step) {
            
                if($step != "" || ctype_space($step))
                {
                    $new_step = $workItem->steps()->create([
                            'detail' => $step
                        ]);

                    $new_step->step_id = 'Step'. str_pad($new_step->id, 3, '0', STR_PAD_LEFT);
                    $new_step->save();
                }
            }
        }


        if(!is_null($workItem))
        {
            $res['success'] = "New Item added!";

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
        return redirect()->route('admin.work-items.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $workItem = WorkItem::find($id);

        if(is_null($workItem))
            return view('admin.404');

        $parts = \App\Part::orderBy('part')->pluck('part', 'id')->toArray();
        $types = \App\Type::orderBy('type')->pluck('type', 'id')->toArray();

        $sub_types = $workItem->itemType->subTypes()->pluck('sub_type', 'id')->toArray();

        $selectedParts = $workItem->parts()->pluck('id')->toArray();

        return view('admin.work-item.edit',
                    compact('workItem', 'parts', 'types', 'sub_types', 'selectedParts'));
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
        // return $request->all();
        $messages = [
            'sub_type_id' => 'The sub type field is required',
            'type_id' => 'The type field is required',
            'est_hrs.required' => 'The estimated hours field is required',
            'est_hrs.numeric' => 'The estimated hours must be a number.'
        ];

        $this->validate($request, [
                'type_id' => 'required|string',
                'sub_type_id' => 'required|string',
                'detail' => 'required|string|max:500',
                'price' => 'required|numeric',
                'est_hrs' => 'required|numeric',
                // 'parts' => 'required',
            ], $messages);

        $workItem = WorkItem::find($id);

        $data = $workItem->update($request->all());

        if( count($request->input('parts')) > 0 )
          $workItem->parts()->sync($request->input('parts'));
        else
          $workItem->parts()->sync([]);

        if($request->has('steps'))
        {
            $delete = $workItem->steps()->delete();

            foreach ($request->get('steps') as $key => $step) {
            
                if($step != "" || ctype_space($step))
                {
                    $new_step = $workItem->steps()->create([
                        'detail' => $step
                    ]);

                    $new_step->step_id = 'Step'. str_pad($new_step->id, 3, '0', STR_PAD_LEFT);
                    $new_step->save();

                    $keep[] = $new_step->id;
                    
                    /*$existing = $workItem->steps()->find($key);

                    $keep[] = $key;

                    if($existing)
                    {
                        $existing->detail = $step;
                        $existing->save();
                    }
                    else
                    {
                        $new_step = $workItem->steps()->create([
                            'detail' => $step
                        ]);

                        $new_step->step_id = 'Step'. str_pad($new_step->id, 3, '0', STR_PAD_LEFT);
                        $new_step->save();

                        $keep[] = $new_step->id;
                    }*/
                    
                }
            }
            /*if(count($workItem->steps) > 0)
            {
                $steps = $workItem->steps()->pluck('id')->toArray();
                $delete = ItemStep::destroy(array_diff($steps, $keep));
            }*/
        }
        else
        {
            $workItem->steps()->delete();
        }

        if(!is_null($data))
        {
            $res['success'] = "Updated!";
            $res['edit_title'] = $workItem->type;
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
        $workItem = WorkItem::find($id);
        if(is_null($workItem))
            return view('admin.404');
        else
        {
            WorkItem::destroy($workItem->id);
            return redirect()->route('admin.work-items.index');
        }
    }

    /**
     * get item price 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getItemPrice(Request $request)
    {
        $item = WorkItem::find($request->get('item_id'));
        $res['price'] = number_format(0, 2);
        if($item)
            $res['price'] = $item->price;

        return $res;
    }
}
