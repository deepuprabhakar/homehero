<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Type;
use App\SubType;
use Datatables;
use Form;

class TypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.types.index');
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
        $types = Type::orderBy('type')->get();

        $datatables = Datatables::of($types)
            
            ->addColumn('action', function ($types) {
            
                return '<div class="text-center">
                            <a href="'.route('admin.types.edit', $types->id).'" class="datatable-action btn btn-primary btn-xs">
                                <i class="fa fa-pencil-square"></i></a>'.
                            
                            Form::open(['url' => route('admin.types.destroy', $types->id), 'method' => 'DELETE', 'class' => 'delete-form'])

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
        return view('admin.types.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
                'type' => 'required|string'
            ]);

        $type = Type::create($request->all());

        if(!is_null($type))
        {
            $res['success'] = "New Type added!";
            $res['type'] = $type;
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
        return redirect()->route('admin.types.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = type::find($id);
        if(is_null($type))
            return view('admin.404');

        return view('admin.types.edit', compact('type'));
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
        $this->validate($request, [
                'type' => 'required|string'
            ]);

        $type = Type::find($id);
        
        $data = $type->update($request->all());
        
        if(!is_null($data))
        {
            $res['success'] = "Updated!";
            $res['type'] = $type->type;
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
        $type = Type::find($id);
        if(is_null($type))
            return view('admin.404');
        else
        {
            Type::destroy($type->id);
            return redirect()->route('admin.types.index');
        }
    }

    /**
     * List associated Sub Types
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getSubTypes(Request $request)
    {
        $query = "";
        
        if($request->has('q.term'))
            $query = $request->get('q')['term'];

        return SubType::where('sub_type', 'LIKE', '%'.$query.'%')
                    ->where('type_id', $request->get('type'))
                    ->orderBy('sub_type')
                    ->get();
    }
}
