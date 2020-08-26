<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Staff;
use Mail;
use Datatables;
use DB;
use Form;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.staff.index');
    }

    /**
     * List all Admins
     * @param  Request $request 
     * @return Datatables
     */
    public function getStaff(Request $request)
    {
        // $staff = Staff::orderBy('firstname')->get();

        $staff = Staff::orderBy('firstname')->select([
            DB::raw("CONCAT(staff.firstname,' ',staff.lastname) as first_name"),
            'staff.*'
            ])->get();

        $datatables = Datatables::of($staff)
            ->addColumn('action', function ($staff) {
                return '<div class="text-center">
                            <a href="'.route('admin.staff.edit', $staff->id).'" class="datatable-action btn btn-primary btn-xs">
                                <i class="fa fa-pencil-square"></i></a>'.
                            
                            Form::open(['url' => route('admin.staff.destroy', $staff->id), 'method' => 'DELETE', 'class' => 'delete-form'])

                            .'<button type="submit" class="datatable-action delete btn btn-danger btn-xs" aria-label="Left Align">
                              <i class="fa fa-trash"></i></span>
                            </button>'.

                            Form::close()

                            .'</div>
                        ';
            })
            // filter for contact firstname and lastname - searching
            ->filterColumn('first_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(staff.firstname,' ', staff.lastname) like ?", ["%{$keyword}%"]);
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
        return view('admin.staff.add');
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
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'email' => 'required|email|unique:staff',
                'phone' => 'required|string|unique:staff',
            ]);

        $input = $request->all();
        $password = str_random(6);
        $input['password'] = bcrypt($password);
        
        // create new admin
        $staff = Staff::create($input);

        if(!is_null($staff))
        {
            $data['username'] = $staff->email;
            $data['password'] = $password;
            
            // mail login details to given email
            Mail::send('admin.staff.emails.welcome', ['data' => $data], function ($message) use ($data) {
                
                $message->subject('Home Hero Field Staff Account');
                
                $message->to($data['username']);
            });
            
            $res['success'] = "New Field Staff added!";
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
        return redirect()->route('admin.staff.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $staff = Staff::find($id);
        if(is_null($staff))
            return view('admin.404');

        return view('admin.staff.edit', compact('staff'));
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
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'phone' => 'required|string|unique:staff,phone,'.$id,
                'email'     => 'required|email|unique:staff,email,'.$id
            ]);
        $staff = Staff::find($id);
        $data = $staff->update($request->all());
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
        $staff = Staff::find($id);
        if(is_null($staff))
            return view('admin.404');
        else
        {
            Staff::destroy($staff->id);
            return redirect()->route('admin.staff.index');
        }
    }
}
