<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\PopupPage;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $data = '';

        // Define the path to the JSON file
        $jsonFilePath = 'settings/Settings.json';

        // Check if the JSON file exists
        if (Storage::exists($jsonFilePath)) {
            // Retrieve data from the JSON file
            $data = json_decode(Storage::get($jsonFilePath), true);
        } else {
            // Fetch settings data as key-value pairs from the database
            $settings = Setting::all()->pluck('value', 'key')->toArray();

            // Convert settings to JSON format
            $data = $settings;

            // Store the settings in the JSON file
            Storage::put($jsonFilePath, json_encode($data));
        }

        return response()->json([
            "status" => "success",
            'message' => 'site settings found',
            "data" => $data,
        ], 200);
    }

    public function popup(){
        $popup = PopupPage::with('image')->get();

        return response()->json([
            "status" => "success",
            'message' => 'popup page found',
            "data" => $popup,
        ], 200);
    }
}
