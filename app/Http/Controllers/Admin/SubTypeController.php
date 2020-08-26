<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Type;
use App\SubType;
use Datatables;
use Form;

class SubTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.sub-types.index');
    }

    /**
     * List all Types
     * 
     * @param  Request $request 
     * 
     * @return Datatables
     */
    public function getTypes(Request $request)
    {
        // $types = SubType::with('getType')->select('sub_types.*')->orderBy('sub_type');
        $types = SubType::select('sub_types.*', 'types.type')
                        ->leftJoin('types', 'types.id','=','sub_types.type_id')
                        ->orderBy('sub_types.sub_type')->get();
                      
        $datatables = Datatables::of($types)

            ->addColumn('action', function ($types) {
            
                return '<div class="text-center">
                            <a href="'.route('admin.sub-types.edit', $types->id).'" class="datatable-action btn btn-primary btn-xs">
                                <i class="fa fa-pencil-square"></i></a>'.
                            
                            Form::open(['url' => route('admin.sub-types.destroy', $types->id), 'method' => 'DELETE', 'class' => 'delete-form'])

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
        $types = Type::pluck('type', 'id')->toArray();
        return view('admin.sub-types.add', compact('types'));
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
                'type_id.required' => 'The type field is required.',
                'sub_type.required' => 'The sub type field is required.'
            ];

        $this->validate($request, [
                'type_id' => 'required|string',
                'sub_type'=> 'required|string',
            ], $messages);

        $type = SubType::create($request->all());

        if(!is_null($type))
        {
            $res['success'] = "New Sub Type added!";
            $res['sub_type'] = $type;
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
        return redirect()->route('admin.sub-types.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sub_type = SubType::find($id);
        $types = Type::pluck('type', 'id')->toArray();
        if(is_null($sub_type))
            return view('admin.404');

        return view('admin.sub-types.edit', compact('sub_type', 'types'));
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
                'type_id.required' => 'The type field is required.',
                'sub_type.required' => 'The sub type field is required.'
            ];

        $this->validate($request, [
                'type_id' => 'required|string',
                'sub_type'=> 'required|string',
            ], $messages);

        $type = SubType::find($id);
        
        $data = $type->update($request->all());
        
        if(!is_null($data))
        {
            $res['success'] = "Updated!";
            $res['type'] = $type->sub_type;
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
        $type = SubType::find($id);
        if(is_null($type))
            return view('admin.404');
        else
        {
            SubType::destroy($type->id);
            return redirect()->route('admin.sub-types.index');
        }
    }
}
