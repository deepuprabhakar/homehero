<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Client;
use Validator;
use Config;

class ClientController extends Controller
{
    public function __construct()
    {
        Config::set('auth.providers.users.model', \App\Staff::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = json_decode($request->input('json_data'), true);

        // $clients = Client::search($input['search'], null, true)->get(); #uncomment if search is needed
        $clients = Client::all();

        if(is_null($clients))
            return response()->json([
                    'error' => 'clients_not_found',
                    'status' => false,
                    'message' => 'Clients not found!',
                    'clients' => 0,
                    'no_of_records' => 0
                    ]);

        return response()->json([
                    'error' => 0,
                    'status' => true,
                    'message' => 'Clients found',
                    'no_of_records' => $clients->count(),
                    'clients' => $clients,
                    ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(json_decode($request->input('json_data'), true), [
            'first_name' => 'required',
            'last_name' => 'required',
            /*'home_phone' => 'required',
            'mobile_phone' => 'required',
            'office_phone' => 'required',*/
            'first_address' => 'required',
            // 'second_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'email'     => 'required|email|unique:clients'
        ]);

        if ($validator->fails()) {
            return response()->json([
                    'errors' => $validator->errors(),
                    'status' => false,
                ], 200);
        }

        $input = json_decode($request->input('json_data'), true);

        $client = Client::create($input);

        if(is_null($client))
            return response()->json([
                    'error' => 'client_could_not_be_added',
                    'status' => false,
                    'message' => 'Client could not be added.'
                    ]);

        return response()->json([
                    'error' => 0,
                    'status' => true,
                    'message' => 'Client has been added',
                    'client' => $client->id
                    ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);

        if(is_null($client))
            return response()->json([
                    'error' => 'client_not_found',
                    'status' => false,
                    'message' => 'Client not found!',
                    'client' => 0
                    ]);

        return response()->json([
                    'error' => 0,
                    'status' => true,
                    'message' => 'Client found',
                    'client' => $client
                    ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::find($id);

        if(is_null($client))
            return response()->json([
                    'error' => 'client_not_found',
                    'status' => false,
                    'message' => 'Client not found!',
                    'client' => 0
                    ]);

        return response()->json([
                    'error' => 0,
                    'status' => true,
                    'message' => 'Client found',
                    'client' => $client
                    ]);
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
        // dd($id);
        $client = Client::find($id);

        if(is_null($client))
            return response()->json([
                    'error' => 'client_not_found',
                    'status' => false,
                    'message' => 'Client not found!',
                    'client' => 0
                    ]);

        $validator = Validator::make(json_decode($request->input('json_data'), true), [
            'first_name' => 'required',
            'last_name' => 'required',
            /*'home_phone' => 'required',
            'mobile_phone' => 'required',
            'office_phone' => 'required',*/
            'first_address' => 'required',
            // 'second_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'email'     => 'required|unique:clients,email,'.$id
        ]);

        if ($validator->fails()) {
            return response()->json([
                    'errors' => $validator->errors(),
                    'status' => false,
                ], 200);
        }

        $input = json_decode($request->input('json_data'), true);

        $status = $client->update($input);

        if(!$status)
            return response()->json([
                    'error' => 'client_could_not_be_updated',
                    'status' => false,
                    'message' => 'Client could not be updated.'
                    ]);

        return response()->json([
                    'error' => 0,
                    'status' => true,
                    'message' => 'Client has been updated',
                    'client' => $client
                    ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
