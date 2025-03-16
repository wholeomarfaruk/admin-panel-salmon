<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploadControler extends Controller
{
    public function index(Request $request)
    {
        $fileuploads = FileUpload::orderBy("updated_at","desc")->paginate(10);
        return view("admin.fileupload.index", compact("fileuploads"));
    }
    public function add(Request $request){
        return view("admin.fileupload.fileupload-add");
    }
    public function store(Request $request){

        // dd($request->file('file'));  // Dumps the file data, check what it returns
        $request->validate([
            "file" => "required",
            "name" => "required",
            "type" => "required",
        ]);
        try {


            $path = 'uploads/fileuploads/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $fileName = Str::slug($request->name) . "_" . uniqid() . '.' . $request->file->getClientOriginalExtension();
            $fileSize = $request->file('file')->getSize(); // Get size before moving
            if ($request->file('file')->isValid()) {
                $file = $request->file('file')->move($path, $fileName);
            } else {
                return back()->withErrors(['file' => 'Uploaded file is not valid.']);
            }


            $model_type = null;
            $model_id = null;
            $file_for = "general";

            FileHelper::uploadFile($path . $fileName, $file_for, $model_type, $model_id, [
                "name" => $request->name,
                "type" => $request->type,
                "size" => $fileSize,
            ]);
        } catch (\Exception $e) {
            // Handle the exception as needed
            Log::error('File upload failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['file' => 'File upload failed. Please try again.']);
        }

     return redirect()->route("admin.fileupload.list")->with("success", "fileupload added successfully");
    }
    public function edit(Request $request,$id){

    }
    public function update(Request $request,$id){

    }
    public function delete(Request $request,$id){

        $fileupload = FileUpload::find($id);

        if (!$fileupload) {
            return redirect()->back()->with("error", "fileupload not found");
        }
        try {
            //code...
            FileHelper::deleteFile($fileupload->id);

            return redirect()->back()->with("success", "fileupload deleted successfully");
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }
    public function bulkDelete(Request $request){

        $fileuploads = FileUpload::whereIn("id", $request->ids)->get();
        // return request()->all();
        if (!$fileuploads) {
            return redirect()->back()->with("error", "fileupload not found");
        }
        try {
            //code...
            foreach ($fileuploads as $fileupload) {

                FileHelper::deleteFile($fileupload->id);
            }

            return response()->json(['success' => 'fileuploads deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function chunkUpload(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'file' => 'required|file',  // Ensure file is present
            'chunkIndex' => 'required|integer',
            'totalChunks' => 'required|integer',
            'fileName' => 'required|string',
            'identifier' => 'required|string'
        ]);

        // Handle the file upload
        $chunkIndex = $request->input('chunkIndex');
        $totalChunks = $request->input('totalChunks');
        $fileName = $request->input('fileName');
        $identifier = $request->input('identifier');

        // Create a temp folder for the chunks
        $tempPath = storage_path("app/uploads/temp/{$identifier}");

        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0777, true);
        }

        // Save the chunk to a temporary file
        $chunkFilePath = "{$tempPath}/chunk_{$chunkIndex}";
        file_put_contents($chunkFilePath, file_get_contents($request->file('file')->getPathname()));

        // If it's the last chunk, combine all chunks and remove the temp folder
        if ($chunkIndex == $totalChunks - 1) {
            $finalPath = storage_path("app/uploads/{$fileName}");
            $outputFile = fopen($finalPath, 'wb');

            for ($i = 0; $i < $totalChunks; $i++) {
                fwrite($outputFile, file_get_contents("{$tempPath}/chunk_{$i}"));
                unlink("{$tempPath}/chunk_{$i}");
            }

            fclose($outputFile);
            rmdir($tempPath);

            return response()->json([
                'success' => true,
                'file_path' => asset("storage/uploads/{$fileName}"),
                'message' => 'Upload completed'
            ]);
        }

        return response()->json(['success' => true, 'chunk' => $chunkIndex]);
    }

}
