<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index(){
        return response()->json([
            'Hello Duniya',
        ]);
    }

    // Setting Data Store
    public function store_data(Request $request){
        
        // Validate the request
        $request->validate([
            'site_name' => 'nullable|string',
            'TotalAreaBuilt' => 'nullable|string',
            'NumberofProjects' => 'nullable|string',
            'YearsSinceInception' => 'nullable|string',
            'HappyClients' => 'nullable|string',
        ]);

        // Create or update the setting
        $setting = Setting::updateOrCreate(['key' => 'site_name'], ['value' => $request->site_name]);
        $setting = Setting::updateOrCreate(['key' => 'TotalAreaBuilt'], ['value' => $request->TotalAreaBuilt]);
        $setting = Setting::updateOrCreate(['key' => 'NumberofProjects'], ['value' => $request->NumberofProjects]);
        $setting = Setting::updateOrCreate(['key' => 'YearsSinceInception'], ['value' => $request->YearsSinceInception]);
        $setting = Setting::updateOrCreate(['key' => 'HappyClients'], ['value' => $request->HappyClients]);

        return response()->json([
            'status' => 'success',
            'message' => 'Settings update successfully'
        ], 200);
    }


    public function user_info(Request $request){
        // Validate the request
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Update the user info
        $user = $request->user();
        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        $user->password = bcrypt($request->password) ?? $user->password;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User info update successfully'
        ], 200);
    }
}
