<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ModelType;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OurStoryController extends Controller
{
    public function ourStoryEdit(Request $request){
        $our_story_images = FileUpload::where('model_type', ModelType::OurStory->value)->get();
        // return $our_story_images;
        return view("admin.ourstory.index", compact("our_story_images"));
    }
    public function ourStoryUpdate(Request $request,$id){


        $request->validate([
            'name' => 'required',
            'file' => 'required',
        ]);
        $image = FileUpload::find($id);
        if(!$image){
            return back()->with('error','Image not found');
        }
        $image->name = $request->name;

        $image->save();
        // Update slider
        if ($request->hasFile('file')) {
            $oldThumbnail = $image;
            if ($oldThumbnail) {
                FileHelper::deleteFile($oldThumbnail->id);
            }

            $path = 'uploads/ourestory/images/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileSize = $request->file('file')->getSize();

            $fileName = Str::slug($request->name) . "_" . Str::uuid() . "." . $request->file('file')->getClientOriginalExtension();
            $request->file('file')->move($path, $fileName);

            FileHelper::uploadFile($path . $fileName, $image->file_for, ModelType::OurStory->value, null, [
                'name' => $request->name,
                'type' => 'image',
                'size' => $fileSize,
            ]);
        }
        return redirect()->back()->with("success","Image updated successfully");
    }
}
