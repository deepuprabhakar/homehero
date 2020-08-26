<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Location;
use Datatables;
use Form;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.location.index');
    }

    /**
     * List all Admins
     * 
     * @param  Request $request 
     * 
     * @return Datatables
     */
    public function getLocations(Request $request)
    {
        $locations = Location::orderBy('type')->orderBy('sub_type')->get();

        $datatables = Datatables::of($locations)
            
            ->addColumn('action', function ($locations) {
            
                return '<div class="text-center">
                            <a href="'.route('admin.locations.edit', $locations->id).'" class="datatable-action btn btn-primary btn-xs">
                                <i class="fa fa-pencil-square"></i></a>'.
                            
                            Form::open(['url' => route('admin.locations.destroy', $locations->id), 'method' => 'DELETE', 'class' => 'delete-form'])

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
        return view('admin.location.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
                'type' => 'required|string',
                'sub_type'  => 'required|string',
                // 'loc_id' => 'required|regex:/Loc([0-9])+$/|unique:locations',
                ];

        $messages = [
                'loc_id.required' => 'The location ID field is required.',
                'loc_id.regex' => 'Format must be "Loc" followed by numbers.',
                'loc_id.unique' => 'The location ID has already been taken.',
                ];                

        $this->validate($request, $rules, $messages);

        // create new location
        $location = Location::create($request->all());

        $location->loc_id = 'Loc'. str_pad($location->id, 3, '0', STR_PAD_LEFT);

        $location->save();

        if(!is_null($location))
        {
            $res['success'] = "New Location added!";
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
        return redirect()->route('admin.locations.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $location = Location::find($id);
        if(is_null($location))
            return view('admin.404');

        return view('admin.location.edit', compact('location'));
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
        $rules = [
                'type' => 'required|string',
                'sub_type'  => 'required|string',
                // 'loc_id' => 'required|regex:/Loc([0-9])+$/|unique:locations,loc_id,'.$id,
                ];

        $messages = [
                'loc_id.required' => 'The location ID field is required.',
                'loc_id.regex' => 'Format must be "Loc" followed by numbers.',
                'loc_id.unique' => 'The location ID has already been taken.',
                ];                

        $this->validate($request, $rules, $messages);
        
        $location = Location::find($id);
        
        $data = $location->update($request->all());
        
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
        $location = Location::find($id);
        if(is_null($location))
            return view('admin.404');
        else
        {
            Location::destroy($location->id);
            return redirect()->route('admin.locations.index');
        }
    }
}
