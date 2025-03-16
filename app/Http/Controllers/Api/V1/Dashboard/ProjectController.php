<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Models\Project;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectStoreDataRequest;
use App\Http\Controllers\helper\FileController;
use App\Http\Controllers\helper\MediaController;
use Faker\Core\File;

class ProjectController extends Controller
{


    // Guest Project Data
    public function projects(){
        $projects = Project::with(['banner', 'thumbnail', 'pdf', 'amenities_images', 'gallery'])->paginate(10);

        return response()->json([
            'status' => 'success',
            'projects' => $projects,
        ], 200);
    }

    // Guest Single Project Data
    public function project($id){
        $project = Project::with(['banner', 'thumbnail', 'pdf', 'amenities_images', 'gallery'])->where('id', $id)->first();

        if(!$project){
            return response()->json([
                'status' => 'error',
                'code' => 1004,
                'message' => 'Project not found.',
            ], 404);
        }


        return response()->json([
            'status' => 'success',
            'project' => $project,
        ], 200);
    }

    // Auth
    public function store_data(ProjectStoreDataRequest $request)
    {

        if($request->file('thumbnail')){
            $thumbnailFile = $request->file('thumbnail') ? 'uploads/projects/' . MediaController::uploadFile($request->file('thumbnail'), 'uploads/projects/') : ""; // upload new file
        }else{
            $thumbnailFile = "";
        }

        $project = Project::create([
            'title' => $request->input('title'),
            'sub_title' => $request->input('sub_title'),
            'status' => $request->input('status'),
            'type' => $request->input('type'),
            'start_date' => $request->input('start_date'),
            'target_to_complete_date' => $request->input('target_to_complete_date'),
            'completion_date' => $request->input('completion_date'),
            'location' => $request->input('location'),
            'landmark_lat_long' => $request->input('landmark_lat_long'),
            'project_extra_data' => $request->input('project_extra_data'),
            'videos' => $request->input('videos'),
            'description' => $request->input('description'),
            'thumbnail' => $thumbnailFile,
        ]);

        $project_id = $project->id;
        $project_title = $project->title;

        // Get the 'images' files from the request
        $images = $request->file('images');
        if ($images) {
            // Loop through each image
            foreach ($images as $image) {
                FileController::file_upload('uploads/projects_gallery/' . MediaController::uploadFile($image, 'uploads/projects_gallery'), $project_id, $project_title, 1);  // 1 for project image type in file_upload table of db
            }
        }

        if ($project) {
            return response()->json([
                'status' => 'success',
                'message' => 'Project created successfully.',
                'project_id' => $project->id,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'code' => 1003,
                'message' => 'Failed to create project.',
            ], 500);
        }

    }


    // Update Project Data
    public function update_data(ProjectStoreDataRequest $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'code' => 1004,
                'message' => 'Project not found.',
            ], 404);
        }


        $project->update([
            'title' => $request->input('title'),
            'sub_title' => $request->input('sub_title'),
            'status' => $request->input('status'),
            'type' => $request->input('type'),
            'start_date' => $request->input('start_date'),
            'target_to_complete_date' => $request->input('target_to_complete_date'),
            'completion_date' => $request->input('completion_date'),
            'location' => $request->input('location'),
            'landmark_lat_long' => $request->input('landmark_lat_long'),
            'project_extra_data' => $request->input('project_extra_data'),
            'videos' => $request->input('videos'),
            'description' => $request->input('description'),
            // 'thumbnail' => $thumbnailFile,
        ]);

        if($request->hasFile('thumbnail')){

            if($project->thumbnail){
                MediaController::deleteFile($project->thumbnail); // deleted existing file
            }

            $thumbnailFile = 'uploads/projects/' . MediaController::uploadFile($request->file('thumbnail'), 'uploads/projects'); // upload new file
        }

        $project->update([
            'thumbnail' => $thumbnailFile ?? $project->thumbnail,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Project updated successfully.',
            'project_id' => $project->id,
        ], 200);
    }

    // Delete Project Data
    public function delete_data($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'code' => 1004,
                'message' => 'Project not found.',
            ], 404);
        }



        $files = FileUpload::where('reff_id', $id)->get();
        foreach ($files as $file) {
            MediaController::deleteFile($file->file);
            $file->delete();
            $FileController = new FileController();
            $FileController->deleteFile($file->id);

        }

        MediaController::deleteFile($project->thumbnail); // deleted existing file
        $project->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Project deleted successfully.',
        ], 200);
    }

}
