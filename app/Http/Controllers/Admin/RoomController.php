<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Room;
use Datatables;
use Form;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.room.index');
    }

    /**
     * List all Rooms
     * 
     * @param  Request $request 
     * 
     * @return Datatables
     */
    public function getRooms(Request $request)
    {
        $rooms = Room::orderBy('area')->get();

        $datatables = Datatables::of($rooms)
            
            ->addColumn('action', function ($rooms) {
            
                return '<div class="text-center">
                            <a href="'.route('admin.rooms.edit', $rooms->id).'" class="datatable-action btn btn-primary btn-xs">
                                <i class="fa fa-pencil-square"></i></a>'.
                            
                            Form::open(['url' => route('admin.rooms.destroy', $rooms->id), 'method' => 'DELETE', 'class' => 'delete-form'])

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
        return view('admin.room.add');
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
                'area' => 'required|string'
            ]);

        $room = Room::create($request->all());

        $room->room_id = 'Room'. str_pad($room->id, 3, '0', STR_PAD_LEFT);

        $room->save();

        if(!is_null($room))
        {
            $res['success'] = "New Room added!";
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
        return redirect()->route('admin.rooms.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $room = Room::find($id);
        if(is_null($room))
            return view('admin.404');

        return view('admin.room.edit', compact('room'));
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
                'area' => 'required|string'
            ]);

        $room = Room::find($id);
        
        $data = $room->update($request->all());
        
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
        $room = Room::find($id);
        if(is_null($room))
            return view('admin.404');
        else
        {
            Room::destroy($room->id);
            return redirect()->route('admin.rooms.index');
        }
    }
}
