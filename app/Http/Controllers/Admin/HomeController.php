<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ModelType;
use App\Enums\UploadFileTypes;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Explore;
use App\Models\FileUpload;
use App\Models\Project;
use App\Models\Stats;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{


    //Slider Start==================================================================================
    public function sliderIndex()
    {
        $slider = FileUpload::where('model_type', ModelType::Home->value)
            ->where('file_for', 'slider')
            ->latest()
            ->paginate(10);
        $counter = ($slider->currentPage() - 1) * $slider->perPage();
        foreach ($slider as $index => $slide) {
            $slide->counter = $counter + $index + 1;
        }

        return view('admin.home.slider.index', compact('slider'));
    }
    public function sliderAdd(Request $request)
    {
        return view('admin.home.slider.slider-add');
    }
    public function sliderStore(Request $request)
    {

        $request->validate([
            'slide' => 'required|image|mimes:png,jpg,jpeg,webp,gif|max:2024',
            'name' => 'required',
        ]);

        if ($request->hasFile('slide')) {
            $path = 'uploads/home/slider/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileSize = $request->file('slide')->getSize(); // Get size before moving

            $imageRename = Str::slug($request->name) . "_" . Str::uuid() . "_size_." . $request->file('slide')->getClientOriginalExtension();
            $request->file('slide')->move($path, $imageRename);

            FileHelper::uploadFile($path . $imageRename, 'slider', ModelType::Home->value, null, [
                'name' => $request->name,
                'type' => 'image',
                'size' => $fileSize,
            ]);
        }

        return redirect()->route('admin.home.slider.list')->with('success', 'Slider added successfully');
    }
    public function sliderEdit($id)
    {
        $slider = FileUpload::find($id);
        return view('admin.home.slider.slider-edit', compact('slider'));
    }
    public function sliderUpdate(Request $request, $id)
    {

        $request->validate([
            'name' => 'required',
        ]);
        $slider = FileUpload::find($id);
        if (!$slider) {
            return redirect()->back()->with('error', 'Slider not found');
        }
        $slider->name = $request->name;
        $slider->save();


        // Update slider
        if ($request->hasFile('slide')) {
            $oldThumbnail = $slider;
            if ($oldThumbnail) {
                FileHelper::deleteFile($oldThumbnail->id);
            }

            $path = 'uploads/home/slider/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileSize = $request->file('slide')->getSize();

            $fileName = Str::slug($request->slug) . "_" . Str::uuid() . "." . $request->file('slide')->getClientOriginalExtension();
            $request->file('slide')->move($path, $fileName);

            FileHelper::uploadFile($path . $fileName, 'slider', ModelType::Home->value, $slider->id, [
                'name' => $request->name,
                'type' => 'image',
                'size' => $fileSize,
            ]);
        }

        return redirect()->route('admin.home.slider.list')->with('success', 'Slider updated successfully');

    }
    public function sliderDelete($id)
    {

        $slider = FileUpload::find($id);

        if (!$slider) {
            return redirect()->back()->with("error", "Slide not found");
        }
        try {
            //code...
            FileHelper::deleteFile($slider->id);

            return redirect()->back()->with("success", "Slide deleted successfully");
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }


    //Slider end==================================================================================



    //Explore start==================================================================================

    public function exploreIndex()
    {
        $explores = Explore::latest()->paginate(10);
        $counter = ($explores->currentPage() - 1) * $explores->perPage();
        foreach ($explores as $index => $explore) {
            $explore->counter = $counter + $index + 1;
        }

        return view('admin.home.explore.index', compact('explores'));
    }

    public function exploreAdd(Request $request)
    {
        $projects = Project::all();
        return view('admin.home.explore.explore-add', compact('projects'));
    }
    public function exploreStore(Request $request)
    {

        // return $request->all();
        $request->validate([
            'video_url' => 'required|mimetypes:video/*|max:30720',
            'name' => 'required',
            'project_id' => 'required',
          

        ]);
        $explore = new Explore();
        $explore->name = $request->name;

        $explore->project_id = $request->project_id;
        $explore->save();

        if ($request->hasFile('video_url')) {
            $path = 'uploads/home/explore/videos/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileSize = $request->file('video_url')->getSize(); // Get size before moving

            $imageRename = Str::slug($request->name) . "_" . Str::uuid() . "_size_." . $request->file('video_url')->getClientOriginalExtension();
            $request->file('video_url')->move($path, $imageRename);

            FileHelper::uploadFile($path . $imageRename, 'video', ModelType::Explore->value, $explore->id, [
                'name' => $request->name,
                'type' => UploadFileTypes::Video->value,
                'size' => $fileSize,
            ]);
        }

        return redirect()->route('admin.home.explore.list')->with('success', 'explore added successfully');
    }

    public function exploreEdit($id)
    {
        $explore = Explore::find($id);
        $projects = Project::all();
        return view('admin.home.explore.explore-edit', compact('explore', 'projects'));
    }
    public function exploreUpdate(Request $request, $id)
    {

        // return $request->all();
        $request->validate([

            'name' => 'required',
            'project_id' => 'required',


        ]);


        $explore = Explore::find($id);
        $explore->name = $request->name;

        $explore->project_id = $request->project_id;
        $explore->save();

        if ($request->hasFile('video_url')) {
            $path = 'uploads/home/explore/videos/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            if(!$request->hasFile('video_url')){
                return redirect()->route('admin.home.explore.list')->with('error', 'Video not found');
            }
            $fileSize = $request->file('video_url')->getSize(); // Get size before moving

            $fileRename = Str::slug($request->name) . "_" . Str::uuid() . "_size_." . $request->file('video_url')->getClientOriginalExtension();
            $request->file('video_url')->move($path, $fileRename);

            FileHelper::uploadFile($path . $fileRename, 'video', ModelType::Explore->value, $explore->id, [
                'name' => $request->name,
                'type' => UploadFileTypes::Video->value,
                'size' => $fileSize,
            ]);
        }

        return redirect()->route('admin.home.explore.list')->with('success', 'explore Updated successfully');
    }
    public function exploreDelete($id)
    {
        $explore = Explore::find($id);
        if (!$explore) {
            return redirect()->back()->error('Explore item not found');
        }
        $file = $explore->video;
        if ($file) {
            FileHelper::deleteFile($file->id);
        }
        $explore->delete();
        return redirect()->route('admin.home.explore.list')->with('success', 'Explore item deleted successfully');
    }

    //Explore end==================================================================================
//Stat start==================================================================================
    public function statsEdit()
    {
        $stats = Stats::all();
        return view('admin.home.stats.stats-edit', compact('stats'));
    }
    public function statsUpdate(Request $request, $id)
    {
        // return $request->all();

        $request->validate([
            'title' => 'required',
            'stats' => 'required',
        ]);
        $stats = Stats::find($id);
        $stats->title = $request->title;
        $stats->stats = $request->stats;
        $stats->save();

        // Update image
        if ($request->hasFile('image')) {
            $oldThumbnail = $stats->image;
            if ($oldThumbnail) {
                FileHelper::deleteFile($oldThumbnail->id);
            }

            $path = 'uploads/home/stats/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileSize = $request->file('image')->getSize();

            $fileName = Str::slug($request->title) . "_" . Str::uuid() . "." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($path, $fileName);

            FileHelper::uploadFile($path . $fileName, 'stats', ModelType::Stats->value, $stats->id, [
                'name' => $request->title,
                'type' => 'image',
                'size' => $fileSize,
            ]);
        }
        return redirect()->route('admin.home.stats.edit')->with('success', 'Stats updated successfully');
    }
    //Stat end==================================================================================
}
