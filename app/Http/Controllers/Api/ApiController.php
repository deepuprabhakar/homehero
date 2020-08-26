<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use Config;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Staff;
use Mail;
use Validator;


class ApiController extends Controller
{

    public function __construct()
    {
        Config::set('auth.providers.users.model', \App\Staff::class);
    }

	/**
	 * Authenticate staff using give credentials
	 * @param  Request $request [description]
	 * @return [string]           [token]
	 */
    public function login(Request $request)
    {
        /*$validator = Validator::make(json_decode($request->input('json_data'), true), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                    'errors' => $validator->errors(),
                    'status' => false,
                ], 200);
        }*/

        // grab credentials from the request
        $credentials = json_decode($request->input('json_data'), true);
        
        Config::set('auth.providers.users.model', \App\Staff::class);

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'invalid_credentials',
                    'status' => false,
                    'message' => 'Invalid credentials'
                    ], 200);
            }
            
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json([
                    'error' => 'could_not_create_token',
                    'status' => false,
                    'message' => 'Could not create token'
                    ], 200);
        }

        $user = Auth::user();
        $message = "Login successfull";
        $status = true;
        // all good so return the data
        return response()->json(compact('token', 'user', 'message', 'status'));
    }

    public function resetPassword(Request $request)
    {
        $credentials = json_decode($request->input('json_data'), true);
        
        try {

            $staff = Staff::where('email', $credentials['email'])->first(); 
            // dd($staff);
            if(is_null($staff))
            {
                return response()->json([
                    'error' => 'recource_not_found',
                    'status' => false,
                    'message' => 'Email not found in our records'
                    ], 200);  
            }

        } catch (\Exception $e) {
            return response()->json([
                    'error' => 'something_went_wrong',
                    'status' => false,
                    'message' => 'Something went wrong'
                    ], 200); 

        }

        $password = str_random(6);

        $staff->password = bcrypt($password);
        $staff->save();

        // mail login details to given email
        Mail::send('admin.staff.emails.password', ['staff' => $staff, 'password' => $password], function ($message) use ($staff, $password) {
            
            $message->subject('Home Hero Password Reset');
            
            $message->to($staff['email']);
        });

        return response()->json([
                    'status' => true,
                    'message' => 'Your password has been reset and sent to your email.'
                    ], 200); 
    }

    public function multimail()
    {
        $admins = \App\Admin::where('admin', 1)->pluck('email')->toArray();
        // $emails = ['deepupv91@gmail.com', 'deepu@offshorent.com'];
        // dd($admins);
        // mail notification to admins
        Mail::send('admin.emails.multimail', [], function ($message) use ($admins) {
            $message->from('no-reply@homehero.com', 'Home Hero');
            $message->subject('New Proposal');
            
            // $message->to(Config::get('app.app_mail'));
            $message->to($admins);
        });

        return Mail:: failures();
    }

}
