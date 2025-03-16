<?php
namespace App\Helpers;

use App\Models\FileUpload;

use Illuminate\Support\Facades\Storage;
class FileHelper
{
    /**
     * Handle file upload or insert without file
     *
     * @param object|null $file File from request (nullable)
     * @param string $fileFor Purpose: 'thumbnail', 'gallery', 'slider', 'general'
     * @param int|null $modelType Model type (e.g., 1=Destination, 2=Country)
     * @param int|null $modelId Model row ID
     * @param array $extraParams Additional parameters like name, type, size
     * @return FileUpload |null
     */
    public static function uploadFile($filePath=null, $fileFor, $modelType = null, $modelId = null, $extraParams = [])
    {

        $fileType = $extraParams['type'] ?? null;
        $fileSize = $extraParams['size'] ?? null;
        $fileName = $extraParams['name'] ?? null;


        // Insert into database
        return FileUpload::create([
            'name' => $fileName,
            'file' => $filePath,
            'type' => $fileType,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'file_for' => $fileFor,
            'size' => $fileSize,
        ]);
    }


     /**
     * Delete a file from storage and database
     *
     * @param int $fileId File ID
     * @return bool
     */
    public static function deleteFile($fileId)
    {
        $media = FileUpload::find($fileId);


        if (!$media) {
            return false; // File not found
        }

        // Delete the physical file if it's stored locally
        if ($media->file && file_exists(public_path($media->file))) {
        
            unlink(public_path($media->file));
        }

        // Delete the database record
        return $media->delete();
    }



    public static function updateSingleFile($fileId,$filePath, $fileFor, $modelType = null, $modelId = null, $extraParams = [])
    {


        $thumbnail = FileUpload::find($fileId);
        if ($thumbnail) {
            // Delete the old file from storage
            // if (file_exists(public_path($thumbnail->file))) {
            //     unlink(public_path($thumbnail->file));
            // }

            $thumbnail->file = $filePath;
            $thumbnail->save();
            return $thumbnail;
        }



        return self::uploadFile($filePath, $fileFor, $modelType, $modelId, $extraParams);
    }
}
