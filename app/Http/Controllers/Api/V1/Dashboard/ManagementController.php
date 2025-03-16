<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Management;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    public function managements(){
        $management = Management::with('image')->orderByRaw('`order` IS NULL, `order` ASC')->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Management found',
            'data' => $management,
        ], 200);
    }


    public function management($id){
        $management = Management::with('image')->find($id);

        if(!$management){
            return response()->json([
                'status' => 'error',
                'message' => 'Management not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Management found',
            'data' => $management,
        ], 200);
    }


}
