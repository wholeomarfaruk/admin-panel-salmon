<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ManagementFileTypes;
use App\Enums\ModelType;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;

use App\Models\Management;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManagementController extends Controller
{
     //management list show or index
     public function index()
     {
         $managements = Management::orderByRaw('`order` IS NULL, `order` ASC')->paginate(20);

         return view("admin.management.index", compact("managements"));
     }

     //management add page show
     public function add()
     {
         return view("admin.management.management-add");
     }

     //management store
     public function store(Request $request)
     {
         $request->validate([
             "name"=> "required",
             'description' => 'required',
             'image'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
         ]);

         $management = Management::create([
             'name' => $request->name,
             'designation' => $request->designation ?? null,
             'description' => $request->description ?? null,
             'order' => $request->order ?? null,


         ]);

         if ($request->hasFile('image')) {
             $path = 'uploads/management/images/';
             if (!file_exists($path)) {
                 mkdir($path, 0777, true);
             }

             $fileSize = $request->file('image')->getSize(); // Get size before moving
             $imageRename = Str::slug($request->name) . "_" . Str::uuid() . "_size_." . $request->file('image')->getClientOriginalExtension();
             $request->file('image')->move($path, $imageRename);

             FileHelper::uploadFile($path . $imageRename, ManagementFileTypes::image->value, ModelType::Management->value, $management->id, [
                 'name' => $request->name,
                 'type' => 'image',
                 'size' => $fileSize,
             ]);
         }



         return redirect()->route('admin.management.list')->with('success', 'Management Member added successfully');
     }

     //management edit page show
     public function edit($id)
     {
         $management = Management::find($id);
         return view("admin.management.management-edit", compact("management"));
     }

     //management update
     public function update(Request $request, $id)
     {
         $request->validate([
             "name" => "required",

             "description" => "required",
         ]);

         $management = Management::find($id);

         if (!$management) {
             return redirect()->back()->with("error", "Management Member not found");
         }
         try {
             //code...

         $management->name = $request->name;

         if($request->has('designation')){

             $management->designation = $request->designation;
         }
         $management->description = $request->description;
         if($request->has('order')){
             if(empty($request->order)){
             $management->order = null;
             }else{
                 $management->order = intval($request->order);
             }
         }

         $management->save();

         // Update image
         if ($request->hasFile('image')) {
             $oldThumbnail = $management->image;
             if ($oldThumbnail) {
                 FileHelper::deleteFile($oldThumbnail->id);
             }

             $path = 'uploads/management/images/';
             $fileSize = $request->file('image')->getSize();
             $fileName = Str::slug($request->name) . "_" . Str::uuid() . "." . $request->file('image')->getClientOriginalExtension();
             $request->file('image')->move($path, $fileName);

             FileHelper::uploadFile($path . $fileName, ManagementFileTypes::image->value, ModelType::Management->value, $management->id, [
                 'name' => $request->name,
                 'type' => 'image',
                 'size' => $fileSize,
             ]);
         }
     } catch (\Throwable $th) {
         //throw $th;
         return redirect()->back()->withInput()->with('error', $th->getMessage());
     }


         return redirect()->route('admin.management.list')->with('success', 'Management Member updated successfully');
     }

     //delete
     public function delete($id)
     {
         $management = Management::find($id);

         if (!$management) {
           return redirect()->back()->error('Management Member not found');
         }

         $files = $management->files;

         if($files){
             foreach ($files as $file) {

                 FileHelper::deleteFile($file->id);

             }
         }


         $management->delete();

         return redirect()->route('admin.management.list')->with('success', 'Management Member deleted successfully');

     }

}
