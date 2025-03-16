<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function Locations(){
        $location = Location::paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Location found',
            'data' => $location,
        ], 200);
    }


    public function location($id){
        $location = Location::find($id);

        if(!$location){
            return response()->json([
                'status' => 'error',
                'message' => 'Location not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Location found',
            'data' => $location,
        ], 200);
    }


}
