<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Location;
use App\Room;
use App\Part;
use App\ItemStep;
use Config;

class DataController extends Controller
{
    public function __construct()
    {
        Config::set('auth.providers.users.model', \App\Staff::class);
    }

    public function getData()
    {
    	// Get all locations
    	$locations = Location::all();

    	// Get all rooms
    	$rooms = Room::all();

    	// Get all parts
    	$parts = Part::with('workItems')->get();

        // Get all steps
    	$steps = ItemStep::all();

        // Get all Work Items
        $items = \App\WorkItem::with('parts')->with('steps')->get();

        // Get all types
        $types = \App\Type::all();

        // Get all sub types
        $sub_types = \App\SubType::all();

        return response()->json([
    			'locations' => $locations,
    			'rooms'     => $rooms,
    			'parts'     => $parts,
                'types'     => $types,
                'sub_types' => $sub_types,
                'items'     => $items,
                'steps'     => $steps,
    		]);
    }
}
