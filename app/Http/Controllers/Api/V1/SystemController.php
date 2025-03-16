<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SystemController extends Controller
{
    public function clear(){
        $jsonFilePath = 'settings/Settings.json';
        if (Storage::exists($jsonFilePath)) {
            // Delete the JSON file
            Storage::delete($jsonFilePath);

            return response()->json([
                'status' => 'success',
                'message' => 'Cleared'
            ], 200);
        }
    }
}
