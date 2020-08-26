<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\Admin;
use Datatables;
use Form;
use Mail;
use Config;
use Auth;
use Hash;
use Response;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * List all Admins
     * @param  Request $request 
     * @return Datatables
     */
    public function getAdmins(Request $request)
    {
        // fetch admin data
        
        $admins = Admin::where('super_admin', '!=', 1)
                        ->orderBy('firstname')
                        ->select([
                        DB::raw("CONCAT(admins.firstname,' ',admins.lastname) as first_name"),
                            'admins.*'
                        ])->get();
        
        // assign admin data to Datatables
        $datatables = Datatables::of($admins)

            //add new column for edit and delete
            ->addColumn('action', function ($admins) {
        
                // Action buttons
                return '<div class="text-center">
                            <a href="'.route('admin.admins.edit', $admins->id).'" class="datatable-action btn btn-primary btn-xs">
                                <i class="fa fa-pencil-square"></i></a>'.
                            
                            Form::open(['url' => route('admin.admins.destroy', $admins->id), 'method' => 'DELETE', 'class' => 'delete-form'])

                            .'<button type="submit" class="datatable-action delete btn btn-danger btn-xs" aria-label="Left Align">
                              <i class="fa fa-trash"></i></span>
                            </button>'.

                            Form::close()

                            .'</div>
                        ';
            })

            ->editColumn('admin', function($admins) {
                if($admins->admin)
                    return '<div class="text-center">Yes</div>';
                else
                    return '<div class="text-center">No</div>';
            })
            
            // filter for contact firstname and lastname - searching
            ->filterColumn('first_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(admins.firstname,' ', admins.lastname) like ?", ["%{$keyword}%"]);
            });
            
        return $datatables->make(true);
    }

    /**
     * Admin Dashboard
     * 
     * @return [type] [description]
     */
    public function dashboard()
    {
        $proposals = \App\Proposal::get()->count();
        $clients = \App\Client::get()->count();
        $staff = \App\Staff::get()->count();

        return view('admin.home', compact('proposals', 'clients', 'staff'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.add');
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
                'phone' => 'required|string|unique:admins',
                'email' => 'required|email|unique:admins',
            ]);


        $input = $request->all();
        $password = str_random(6);
        $input['password'] = bcrypt($password);
        
        // create new admin
        $admin = Admin::create($input);

        if(!is_null($admin))
        {
            $data['username'] = $admin->email;
            $data['password'] = $password;
            $data['actionUrl'] = Config::get('app.url') . '/admin';
            
            // mail login details to given email
            Mail::send('admin.emails.welcome', ['data' => $data], function ($message) use ($data) {
                
                $message->subject('Home Hero Admin Account');
                
                $message->to($data['username']);
            });


            if($request->ajax())
            {
                $res['success'] = "New Admin added!";
                return $res;
            }
            else
            {
                return redirect()->back()->with('success', 'New Admin added!');
            }
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
        return redirect()->route('admin.admins.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::find($id);
        if(is_null($admin))
            return view('admin.404');

        return view('admin.edit', compact('admin'));
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
                'phone' => 'required|string|unique:admins,phone,'. $id,
                'email'     => 'required|email|unique:admins,email,'.$id
            ]);

        $admin = Admin::find($id);
        if(!$request->has('admin'))
        {
            $request->merge(['admin' => 0]);
        }
        $data = $admin->update($request->all());
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
        $admin = Admin::find($id);
        if(is_null($admin))
            return view('admin.404');
        else
        {
            Admin::destroy($admin->id);
            return redirect()->route('admin.admins.index');
        }
    }

    /**
     * Show password reset form
     * @return [type] [description]
     */
    public function getChangePasswordForm()
    {
        return view('admin.change-password');
    }

    /**
     * Reset Password
     * @return [type] [description]
     */
    public function savePassword(Request $request)
    {   
        $this->validate($request, 
            [
                'current_password' => 'required',
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required',
            ]);

        $res['status'] = false;
        $res['status_code'] = 403;
        $res['message'] = '';
        
        // dd(Hash::check($request->get('current_password'), Auth::guard('admin')->user()->password));
        
        if (!Hash::check($request->get('current_password'), Auth::guard('admin')->user()->password)) {
            return response()->json(
                    ['current_password' => "The current password doesn't match with our records"]
                    , 422);
        }

        try { 

            $admin = Auth::guard('admin')->user();
            $admin->password = bcrypt( $request->get('password') );
            $admin->save();

            $res['status'] = true;
            $res['status_code'] = 200;
            $res['message'] = 'Password changed!';
            
        } catch (\Exception $e) {

            $res['status'] = false;
            $res['status_code'] = 500;
            $res['message'] = 'Someting went wrong. Please try again.' . $e->getMessage() . ' on ' . $e->getLine();
            
        } finally {
            return Response::json($res);
        }

    }
}
