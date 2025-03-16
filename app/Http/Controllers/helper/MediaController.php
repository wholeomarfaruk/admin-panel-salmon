<?php

namespace App\Http\Controllers\helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    // Delete File deleteFile("uploads/projects/1737134632.JPG")
    public static function deleteFile($filePath)
    {
        if (file_exists(public_path($filePath))) {
            unlink(public_path($filePath));
        }

        return true;
    }
    

    // Upload File uploadFile($request->file('thumbnail'), "uploads/projects")
    public static function uploadFile($file, $path)
    {
        // $file_name = time() . '.' . $file->getClientOriginalExtension();
        // $file->move(public_path($path), $file_name);
        // return $file_name;

        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($path), $fileName);

        return $fileName;
    }


    
}
