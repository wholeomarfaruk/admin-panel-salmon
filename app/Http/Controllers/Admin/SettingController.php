<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(){
        $settings = Setting::all();
        return view('admin.settings.index',compact('settings'));
    }

    //add new setting
    public function add(){
        return view('admin.settings.settings-add');
    }


    public function store(Request $request){
        if($request->has('key') && Setting::where('key',$request->key)->exists()){
            return redirect()->back()->with('error','this Setting field already exists');
        }
        $setting = new Setting();
        $setting->key = $request->key;
        $setting->value = $request->value;
        $setting->save();
        return redirect()->route('admin.settings.list')->with('success','New Setting field added successfully');
    }
    public function update(Request $request,$id){

        $setting = Setting::find($id);
        if(!$setting){
            return redirect()->back()->with('error','this Setting field not found');
        }

        $index = 0;
        foreach (request()->input('data') as $key => $value) {

            $setting->value = $value;


            $index++;
        }


        $setting->save();
        return redirect()->back()->with('success','Setting updated successfully');
    }
    public function delete($id){
        $setting = Setting::find($id);
        if(!$setting){
            return redirect()->back()->with('error','this Setting field not found');
        }
        $setting->delete();
        return redirect()->back()->with('success','Setting deleted successfully');
    }
}
