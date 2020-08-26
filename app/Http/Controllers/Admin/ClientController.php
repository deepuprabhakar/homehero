<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Client;
use Datatables;
use Form;
use DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.client.index');
    }

    /**
     * List all Admins
     * @param  Request $request 
     * @return Datatables
     */
    public function getClients(Request $request)
    {
        $clients = Client::orderBy('first_name')->select([
            DB::raw("CONCAT(clients.first_name,' ',clients.last_name) as firstname"),
            'clients.*'
            ])->get();

        $datatables = Datatables::of($clients)
            
            ->addColumn('action', function ($clients) {
            
                return '<div class="text-center">
                            <a href="'.route('admin.clients.edit', $clients->id).'" class="datatable-action btn btn-primary btn-xs">
                                <i class="fa fa-pencil-square"></i></a>'.
                            
                            Form::open(['url' => route('admin.clients.destroy', $clients->id), 'method' => 'DELETE', 'class' => 'delete-form'])

                            .'<button type="submit" class="datatable-action delete btn btn-danger btn-xs" aria-label="Left Align">
                              <i class="fa fa-trash"></i></span>
                            </button>'.

                            Form::close()

                            .'</div>
                        ';
            })

            // filter for contact firstname and lastname - searching
            ->filterColumn('firstname', function($clients, $keyword) {
                $clients->whereRaw("CONCAT(clients.first_name,' ', clients.last_name) like ?", ["%{$keyword}%"]);
            })

            ->editColumn('created_at', function(Client $client) {
                return $client->created_at;
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
        return view('admin.client.add');
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
                'first_name' => 'required|string',
                'last_name'  => 'required|string',
                'home_phone' => 'required_without_all:mobile_phone,office_phone',
                'mobile_phone' => 'required_without_all:home_phone,office_phone',
                'office_phone' => 'required_without_all:mobile_phone,home_phone',
                'first_address'   => 'required',
                // 'second_address'   => 'required',
                'city'      => 'required',
                'state'     => 'required',
                'zip'       => 'required|min:5',
                'email'     => 'required|email|unique:clients'
            ], [

                'home_phone.required_without_all'   =>  'At least one phone is required.',
                'mobile_phone.required_without_all'   =>  'At least one phone is required.',
                'office_phone.required_without_all'   =>  'At least one phone is required.',

            ]);

        $input = $request->all();
        
        // create new admin
        $client = client::create($input);

        if(!is_null($client))
        {
            $res['success'] = "New Client added!";
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
        return redirect()->route('admin.clients.index');
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
            return view('admin.404');

        return view('admin.client.edit', compact('client'));
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
                'first_name' => 'required|string',
                'last_name'  => 'required|string',
                'home_phone' => 'required_without_all:mobile_phone,office_phone',
                'mobile_phone' => 'required_without_all:home_phone,office_phone',
                'office_phone' => 'required_without_all:mobile_phone,home_phone',
                'first_address'   => 'required',
                // 'second_address'   => 'required',
                'city'      => 'required',
                'state'     => 'required',
                'zip'       => 'required|min:5',
                'email'     => 'required|email|unique:clients,email,'.$id
            ], [

                'home_phone.required_without_all'   =>  'At least one phone is required.',
                'mobile_phone.required_without_all'   =>  'At least one phone is required.',
                'office_phone.required_without_all'   =>  'At least one phone is required.',

            ]);
        
        $client = Client::find($id);
        
        $data = $client->update($request->all());
        
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
        $client = Client::find($id);
        if(is_null($client))
            return view('admin.404');
        else
        {
            Client::destroy($client->id);
            return redirect()->route('admin.clients.index');
        }
    }
}
