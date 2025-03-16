<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ModelType;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SliderController extends Controller
{
       //management list show or index
       public function index()
       {
           $sliders = Slider::latest()->paginate(20);

           $counter = ($sliders->currentPage() - 1) * $sliders->perPage();
           foreach ($sliders as $index => $slide) {
               $slide->counter = $counter + $index + 1;
           }
           return view("admin.slider.index", compact("sliders"));
       }

       //management add page show
       public function add()
       {
           return view("admin.slider.slider-add");
       }

       //management store
       public function store(Request $request)
       {
           $request->validate([
               "name"=> "required",
               'description' => 'required',
               'slide'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
           ]);

           $slider = Slider::create([
               'name' => $request->name,
               'description' => $request->description ?? null,
           ]);

           if ($request->hasFile('slide')) {
               $path = 'uploads/slider/';
               if (!file_exists($path)) {
                   mkdir($path, 0777, true);
               }

               $fileSize = $request->file('slide')->getSize(); // Get size before moving
               $imageRename = Str::slug($request->name) . "_" . Str::uuid() . "_size_." . $request->file('slide')->getClientOriginalExtension();
               $request->file('slide')->move($path, $imageRename);

               FileHelper::uploadFile($path . $imageRename, 'slider' ,ModelType::Slider->value, $slider->id, [
                   'name' => $request->name,
                   'type' => 'image',
                   'size' => $fileSize,
               ]);
           }



           return redirect()->route('admin.slider.list')->with('success', 'Slider added successfully');
       }

       //management edit page show
       public function edit($id)
       {
           $slider = Slider::find($id);
           return view("admin.slider.slider-edit", compact("slider"));
       }

       //management update
       public function update(Request $request, $id)
       {
           $request->validate([
               "name" => "required",
               "description" => "required",
           ]);

           $slider = Slider::find($id);

           if (!$slider) {
               return redirect()->back()->with("error", "Slider not found");
           }
           try {
               //code...

           $slider->name = $request->name;

           $slider->description = $request->description;

           $slider->save();

           // Update image
           if ($request->hasFile('slide')) {
               $oldThumbnail = $slider->image;
               if ($oldThumbnail) {
                   FileHelper::deleteFile($oldThumbnail->id);
               }

               $path = 'uploads/slider/';
               $fileSize = $request->file('slide')->getSize();
               $fileName = Str::slug($request->name) . "_" . Str::uuid() . "." . $request->file('slide')->getClientOriginalExtension();
               $request->file('slide')->move($path, $fileName);

               FileHelper::uploadFile($path . $fileName, 'slider', ModelType::Slider->value, $slider->id, [
                   'name' => $request->name,
                   'type' => 'image',
                   'size' => $fileSize,
               ]);
           }
       } catch (\Throwable $th) {
           //throw $th;
           return redirect()->back()->withInput()->with('error', $th->getMessage());
       }


           return redirect()->route('admin.slider.list')->with('success', 'Slider updated successfully');
       }

       //delete
       public function delete($id)
       {
           $slider = Slider::find($id);

           if (!$slider) {
             return redirect()->back()->error('Slider not found');
           }

           $files = $slider->files;

           if($files){
               foreach ($files as $file) {

                   FileHelper::deleteFile($file->id);

               }
           }


           $slider->delete();

           return redirect()->route('admin.slider.list')->with('success', 'Slider deleted successfully');

       }
}
