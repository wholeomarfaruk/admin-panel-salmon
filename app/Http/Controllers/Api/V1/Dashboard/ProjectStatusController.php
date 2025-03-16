<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProjectStatus;
use Illuminate\Http\Request;

class ProjectStatusController extends Controller
{
    public function ProjectStatuses(){
        $project_status = ProjectStatus::paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Project Status found',
            'data' => $project_status,
        ], 200);
    }


    public function ProjectStatus($id){
        $project_status = ProjectStatus::find($id);

        if(!$project_status){
            return response()->json([
                'status' => 'error',
                'message' => 'Project Status not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Project Status found',
            'data' => $project_status,
        ], 200);
    }


}
