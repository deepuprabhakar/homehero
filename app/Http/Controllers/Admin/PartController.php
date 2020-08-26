<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Part;
use Datatables;
use Form;
use Log;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.parts.index');
    }

    /**
     * List all Parts
     * 
     * @param  Request $request 
     * 
     * @return Datatables
     */
    public function getParts(Request $request)
    {
        $parts = Part::orderBy('part')->get();

        $datatables = Datatables::of($parts)
            
            ->addColumn('action', function ($parts) {
            
                return '<div class="text-center">
                            <a href="'.route('admin.parts.edit', $parts->id).'" class="datatable-action btn btn-primary btn-xs">
                                <i class="fa fa-pencil-square"></i></a>'.
                            
                            Form::open(['url' => route('admin.parts.destroy', $parts->id), 'method' => 'DELETE', 'class' => 'delete-form'])

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
        return view('admin.parts.add');
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
                'part' => 'required|string|unique:parts',
                'price' => 'required|numeric',
            ], [
                'part.unique' => 'This part name is already taken'
            ]);

        $part = Part::create($request->all());

        $part->part_id = 'Part'. str_pad($part->id, 3, '0', STR_PAD_LEFT);

        $part->save();

        // Log::info('Part added successfully', ['part' => $part->id]);

        if(!is_null($part))
        {
            $res['success'] = "New Part added!";
            $res['part'] = $part;
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
        return redirect()->route('admin.parts.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $part = Part::find($id);
        if(is_null($part))
            return view('admin.404');

        return view('admin.parts.edit', compact('part'));
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
                'part' => 'required|string|unique:parts,part,'.$id,
                'price' => 'required|numeric',
            ]);

        $part = Part::find($id);
        
        $data = $part->update($request->all());
        
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
        $part = Part::find($id);
        if(is_null($part))
            return view('admin.404');
        else
        {
            Part::destroy($part->id);
            return redirect()->route('admin.parts.index');
        }
    }

    /**
     * get part price 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getPartPrice(Request $request)
    {
        $part = Part::find($request->get('part_id'));
        $res['price'] = number_format(0, 2);
        if($part)
            $res['price'] = $part->price;

        return $res;
    }
}
