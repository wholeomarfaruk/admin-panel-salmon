<?php

namespace App\Http\Controllers\helper;

use App\Http\Controllers\Controller;
use App\Models\FileUpload;
use Faker\Core\File;
use Illuminate\Http\Request;

class FileController extends Controller
{


    public static function file_upload($imageFile, $project_id, $title, $type = null){
        
        $fileUpload = FileUpload::create([
            'status' => 1,
            'type' => $type ?? 1, // 1 is project image
            'used' => 1,
            'reff_id' => $project_id,
            'file' => $imageFile,
            'name' => $title,
        ]);

        if($fileUpload){
            return true;
        }
    }

    public function getFile($id){

        $file = FileUpload::find($id);

        if(!$file){
            return response()->json([
                'status' => 404,
                'message' => 'File not found',
                'data' => []
            ], 404);
        }else{
            return response()->json([
                'status' => 200,
                'message' => 'File found',
                'data' => $file
            ], 200);
        }
    }

    public function updateFile(Request $request, $id)
    {
        $request->validate([
            'status' => 'nullable|integer',
            'type' => 'nullable|integer',
            'file' => ['sometimes', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'name' => 'nullable|string',
            'new_path' => 'nullable|string',
        ]);

        $file = FileUpload::find($id);

        if(!$file){
            return response()->json([
                'status' => 404,
                'message' => 'File not found',
                'data' => []
            ], 404);
        }

        $new_path = $request->new_path ?? 'uploads/united/';

        if(request()->file('file')){
            // dd($file->file);
            MediaController::deleteFile($file->file);
            $file->file = $new_path . MediaController::uploadFile(request()->file('file'), $new_path);
            $file->status = $request->status ?? $file->status;
            $file->type = $request->type ?? $file->type;
            $file->name = $request->name ?? $file->name;
            $file->save();
        }

        return response()->json([
            'status' => 200,
            'message' => 'File updated',
            'data' => $file
        ], 200);
    }


    public function deleteFile($id){
        $file = FileUpload::find($id);
        

        if(!$file){
            return response()->json([
                'status' => 404,
                'message' => 'File not found',
                'data' => []
            ], 404);
        }

        if($file->used > 0 || $file->status > 0){
            return response()->json([
                'status' => 400,
                'message' => 'File is in use and active of status',
                'type' => $file->type,
                'reff_id' => $file->reff_id,
            ], 400);
        }
        
        MediaController::deleteFile($file->file);
        $file->delete();

        return response()->json([
            'status' => 200,
            'message' => 'File deleted',
            // 'data' => []
        ], 200);
    }


    public function disableFile($id){
        $file = FileUpload::find($id);
        

        if(!$file){
            return response()->json([
                'status' => 404,
                'message' => 'File not found',
                'data' => []
            ], 404);
        }

        $file->status = 0;
        $file->save();

        return response()->json([
            'status' => 200,
            'message' => 'File disabled',
            'file_id' => $file->id,
            // 'data' => []
        ], 200);
    }
        
}
