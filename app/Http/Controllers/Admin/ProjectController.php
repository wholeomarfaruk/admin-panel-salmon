<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ModelType;
use App\Enums\UploadFileTypes;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helper\FileController;
use App\Http\Controllers\helper\MediaController;
use App\Http\Requests\ProjectStoreDataRequest;
use App\Models\FileUpload;
use App\Models\Location;
use App\Models\Project;
use App\Models\ProjectStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    //show project list on index
    public function index()
    {
        $projects = Project::orderBy("created_at", "desc")->paginate(10);
        return view('admin.project.index', compact('projects'));
    }

    //add project
    public function add()
    {
        $project_statuses = ProjectStatus::all();
        $locations = Location::all();
        return view('admin.project.project-add', compact('project_statuses', 'locations'));
    }

    //store project
    public function store(Request $request)
    {
        // return response()->json($request->all());

        try {
            //code...

        $request->validate([
            'title' => 'required|string|max:255',
            // 'slug' => 'required|string|max:255|unique:projects',
            // 'flat_number' => 'required|string|max:255',
            // 'land_area' => 'required|string|max:255',
            'facing_land' => 'required|string|max:255',
            'floor_number' => 'required|string|max:255',
            'front_road' => 'required|string|max:255',
            'square_ft' => 'required|string|max:255',
            // 'num_car_parking' => 'required|string|max:255',
            'building_type' => 'required|string|max:255',
            'bed_bath_balcony_lift' => 'required|string|max:255',
            // 'description' => 'required|string',
            // 'map_data' => 'required|string|max:255',


            'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg,gif,webp',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg,gif,webp',
            'amenities_images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,gif,webp',
            'gallery.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,gif,webp',
            // 'pdf' => 'required|mimes:pdf|max:2048',
            'is_featured' => 'required',

            // 'location'=> 'required',
            'project_status'=> 'required',
            'ataglanceimage' => 'required|image|mimes:jpeg,png,jpg,gif,svg,gif,webp',


        ]);



        $project = new Project();
        $project->title = $request->input('title');
        $slug = Str::slug($request->title);
        if($request->has('slug')) {
            $slug = Str::slug($request->slug);
        }

        if(Project::where('slug', $slug)->exists()){
            $slug = $slug.'-';
        }
        $project->slug = $slug;
        $project->flat_number = $request->input('flat_number') ?? null;
        $project->land_area = $request->input('land_area') ?? null;
        $project->facing_land = $request->input('facing_land');
        $project->floor_number = $request->input('floor_number');
        $project->front_road = $request->input('front_road');
        $project->square_ft = $request->input('square_ft');
        $project->num_car_parking = $request->input('num_car_parking') ?? null;
        $project->building_type = $request->input('building_type');
        $project->bed_bath_balcony_lift = $request->input('bed_bath_balcony_lift');
        $project->description = $request->input('description') ?? null;
        $project->map_data = $request->input('map_data') ?? null;


        $project->is_featured = $request->input('is_featured');


        $project->location= $request->input('location') ?? null;
        $project->project_status= $request->input('project_status');

        $project->save();

        // Get the 'images' files from the request


        if ($request->hasFile('pdf')) {
            $path = 'uploads/projects/pdfs/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileSize = $request->file('pdf')->getSize(); // Get size before moving

            $imageRename = Str::slug($request->slug) . "_" . Str::uuid() . "__." . $request->file('pdf')->getClientOriginalExtension();
            $request->file('pdf')->move($path, $imageRename);

            FileHelper::uploadFile($path . $imageRename, 'pdf', ModelType::Project->value, $project->id, [
                'name' => $request->title,
                'type' => 'pdf',
                'size' => $fileSize,
            ]);
        }

        if ($request->hasFile('video')) {
            $path = 'uploads/projects/videos/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileSize = $request->file('video')->getSize(); // Get size before moving

            $imageRename = Str::slug($project->slug) . "_" . Str::uuid() . "_size_." . $request->file('video')->getClientOriginalExtension();
            $request->file('video')->move($path, $imageRename);

            FileHelper::uploadFile($path . $imageRename, 'video', ModelType::Project->value, $project->id, [
                'name' => $project->title,
                'type' => UploadFileTypes::Video->value,
                'size' => $fileSize,
            ]);
        }
        if ($request->hasFile('featured_image')) {
            $path = 'uploads/projects/featured_images/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileSize = $request->file('featured_image')->getSize(); // Get size before moving

            $imageRename = Str::slug($request->slug) . "_" . Str::uuid() . "_size_." . $request->file('featured_image')->getClientOriginalExtension();
            $request->file('featured_image')->move($path, $imageRename);

            FileHelper::uploadFile($path . $imageRename, 'featured_image', ModelType::Project->value, $project->id, [
                'name' => $request->title,
                'type' => 'image',
                'size' => $fileSize,
            ]);
        }
        if ($request->hasFile('ataglanceimage')) {
            $path = 'uploads/projects/ataglanceimages/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileSize = $request->file('ataglanceimage')->getSize(); // Get size before moving

            $imageRename = Str::slug($request->slug) . "_" . Str::uuid() . "_size_." . $request->file('ataglanceimage')->getClientOriginalExtension();
            $request->file('ataglanceimage')->move($path, $imageRename);

            FileHelper::uploadFile($path . $imageRename, 'ataglanceimage', ModelType::Project->value, $project->id, [
                'name' => $request->title,
                'type' => 'image',
                'size' => $fileSize,
            ]);
        }

        if ($request->hasFile('banner')) {
            $path = 'uploads/projects/banners/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileSize = $request->file('banner')->getSize(); // Get size before moving

            $imageRename = Str::slug($request->slug) . "_" . Str::uuid() . "_size_." . $request->file('banner')->getClientOriginalExtension();
            $request->file('banner')->move($path, $imageRename);

            FileHelper::uploadFile($path . $imageRename, 'banner', ModelType::Project->value, $project->id, [
                'name' => $request->title,
                'type' => 'image',
                'size' => $fileSize,
            ]);
        }

        if ($request->hasFile('thumbnail')) {
            $path = 'uploads/projects/thumbnails/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $fileSize = $request->file('thumbnail')->getSize(); // Get size before moving
            $imageRename = Str::slug($request->slug) . "_" . Str::uuid() . "_size_." . $request->file('thumbnail')->getClientOriginalExtension();
            $request->file('thumbnail')->move($path, $imageRename);

            FileHelper::uploadFile($path . $imageRename, 'thumbnail', ModelType::Project->value, $project->id, [
                'name' => $request->title,
                'type' => 'image',
                'size' => $fileSize,
            ]);
        }

        if ($request->hasFile('amenities_images')) {
            $path = 'uploads/projects/amenities_images/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            foreach ($request->file('amenities_images') as $image) {


                $fileSize = $image->getSize();
                $imageRename = Str::slug($request->slug) . "_" .Str::uuid() . "_size_." . $image->getClientOriginalExtension();
                $image->move($path, $imageRename);



                FileHelper::uploadFile($path . $imageRename, 'amenities_images', ModelType::Project->value, $project->id, [
                    'name' => $request->title,
                    'type' => 'image',
                    'size' => $fileSize,

                ]);
            }
        }
        if ($request->hasFile('gallery')) {
            $path = 'uploads/projects/gallery/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            foreach ($request->file('gallery') as $slider) {


                $fileSize = $slider->getSize();
                $imageRename = Str::slug($request->slug) . "_" .Str::uuid() . "_size_." . $slider->getClientOriginalExtension();
                $slider->move($path, $imageRename);

                $path . $imageRename;
                FileHelper::uploadFile($path . $imageRename, 'gallery', ModelType::Project->value, $project->id, [
                    'name' => $request->title,
                    'type' => 'image',
                    'size' => $fileSize,

                ]);
            }
        }
    } catch (\Throwable $th) {
        //throw $th;
        return redirect()->back()->with('error', 'Failed to create project. '.$th->getMessage());
    }

    return redirect()->route('admin.project.list')->with('success', 'Project created successfully');


    }


    //edit project
    public function edit($id)
    {
        $project = Project::find($id);
        $locations = Location::all();
        $project_statuses = ProjectStatus::all();
        return view('admin.project.project-edit', compact('project','locations','project_statuses'));
    }


    // Update Project Data
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                // 'slug' => 'required|string|max:255|unique:projects,slug,'.$id,
                // 'flat_number' => 'required|string|max:255',
                // 'land_area' => 'required|string|max:255',
                'facing_land' => 'required|string|max:255',
                'floor_number' => 'required|string|max:255',
                'front_road' => 'required|string|max:255',
                'square_ft' => 'required|string|max:255',
                // 'num_car_parking' => 'required|string|max:255',
                'building_type' => 'required|string|max:255',
                'bed_bath_balcony_lift' => 'required|string|max:255',
                // 'description' => 'required|string',
                // 'map_data' => 'required|string|max:255',


                'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
                'amenities_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
                'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
                // 'pdf' => 'nullable|mimes:pdf',
                'is_featured' => 'required',
                'location'=> 'required',
                'project_status'=> 'required',

                'ataglanceimage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
            ]);

            $project = Project::findOrFail($id);
            $project->title = $request->input('title');


            $slug = Str::slug($request->title);
            if(Project::where('slug', $slug)->where('id', '!=', $id)->exists()){
                $slug = $slug.'-';
            }
            $project->slug = $slug;
            $project->flat_number = $request->input('flat_number') ?? null;
            $project->land_area = $request->input('land_area') ?? null;
            $project->facing_land = $request->input('facing_land');
            $project->floor_number = $request->input('floor_number');
            $project->front_road = $request->input('front_road');
            $project->square_ft = $request->input('square_ft');
            $project->num_car_parking = $request->input('num_car_parking') ?? null;
            $project->building_type = $request->input('building_type');
            $project->bed_bath_balcony_lift = $request->input('bed_bath_balcony_lift');
            $project->description = $request->input('description') ?? null;
            $project->map_data = $request->input('map_data') ?? null;



            $project->is_featured = $request->input('is_featured');


            $project->location= $request->input('location');
            $project->project_status= $request->input('project_status');

            $project->save();

            // update video
            // update video
            if ($request->hasFile('video')) {
                $oldVideo = $project->video;
                if ($oldVideo) {
                    FileHelper::deleteFile($oldVideo->id);
                }
                $path = 'uploads/projects/videos/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $fileSize = $request->file('video')->getSize(); // Get size before moving

                $imageRename = Str::slug($request->slug) . "_" . Str::uuid() . "_size_." . $request->file('video')->getClientOriginalExtension();
                $request->file('video')->move($path, $imageRename);

                FileHelper::uploadFile($path . $imageRename, 'video', ModelType::Project->value, $project->id, [
                    'name' => $request->title,
                    'type' => UploadFileTypes::Video->value,
                    'size' => $fileSize,
                ]);
            }
            // update featured image
            if ($request->hasFile('featured_image')) {
                $oldFeaturedImage = $project->featured_image;
                if ($oldFeaturedImage) {
                    FileHelper::deleteFile($oldFeaturedImage->id);
                }
                $path = 'uploads/projects/featured_images/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $fileSize = $request->file('featured_image')->getSize(); // Get size before moving

                $imageRename = Str::slug($request->slug) . "_" . Str::uuid() . "_size_." . $request->file('featured_image')->getClientOriginalExtension();
                $request->file('featured_image')->move($path, $imageRename);

                FileHelper::uploadFile($path . $imageRename, 'featured_image', ModelType::Project->value, $project->id, [
                    'name' => $request->title,
                    'type' => 'image',
                    'size' => $fileSize,
                ]);
            }
            // update ataglance image
            if ($request->hasFile('ataglanceimage')) {
                $oldAtaglanceImage = $project->ataglanceimage;
                if ($oldAtaglanceImage) {
                    FileHelper::deleteFile($oldAtaglanceImage->id);
                }
                $path = 'uploads/projects/ataglanceimages/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $fileSize = $request->file('ataglanceimage')->getSize(); // Get size before moving

                $imageRename = Str::slug($request->slug) . "_" . Str::uuid() . "_size_." . $request->file('ataglanceimage')->getClientOriginalExtension();
                $request->file('ataglanceimage')->move($path, $imageRename);

                FileHelper::uploadFile($path . $imageRename, 'ataglanceimage', ModelType::Project->value, $project->id, [
                    'name' => $request->title,
                    'type' => 'image',
                    'size' => $fileSize,
                ]);
            }

            // Update PDF
            if ($request->hasFile('pdf')) {
                $oldPdf = $project->pdf;
                if ($oldPdf) {
                    FileHelper::deleteFile($oldPdf->id);
                }

                $path = 'uploads/projects/pdfs/';
                $fileSize = $request->file('pdf')->getSize();
                $fileName = Str::slug($request->slug) . "_" . Str::uuid() . "." . $request->file('pdf')->getClientOriginalExtension();
                $request->file('pdf')->move($path, $fileName);

                FileHelper::uploadFile($path . $fileName, 'pdf', ModelType::Project->value, $project->id, [
                    'name' => $request->title,
                    'type' => 'pdf',
                    'size' => $fileSize,
                ]);
            }

            // Update Banner
            if ($request->hasFile('banner')) {
                $oldBanner = $project->banner;
                if ($oldBanner) {
                    FileHelper::deleteFile($oldBanner->id);
                }

                $path = 'uploads/projects/banners/';
                $fileSize = $request->file('banner')->getSize();
                $fileName = Str::slug($request->slug) . "_" . Str::uuid() . "." . $request->file('banner')->getClientOriginalExtension();
                $request->file('banner')->move($path, $fileName);

                FileHelper::uploadFile($path . $fileName, 'banner', ModelType::Project->value, $project->id, [
                    'name' => $request->title,
                    'type' => 'image',
                    'size' => $fileSize,
                ]);
            }

            // Update Thumbnail
            if ($request->hasFile('thumbnail')) {
                $oldThumbnail = $project->thumbnail;
                if ($oldThumbnail) {
                    FileHelper::deleteFile($oldThumbnail->id);
                }

                $path = 'uploads/projects/thumbnails/';
                $fileSize = $request->file('thumbnail')->getSize();
                $fileName = Str::slug($request->slug) . "_" . Str::uuid() . "." . $request->file('thumbnail')->getClientOriginalExtension();
                $request->file('thumbnail')->move($path, $fileName);

                FileHelper::uploadFile($path . $fileName, 'thumbnail', ModelType::Project->value, $project->id, [
                    'name' => $request->title,
                    'type' => 'image',
                    'size' => $fileSize,
                ]);
            }

            // Update Amenities Images
            if ($request->hasFile('amenities_images')) {
                $oldAmenities = $project->amenities_images;
                foreach ($oldAmenities as $file) {
                    FileHelper::deleteFile($file->id);
                }

                $path = 'uploads/projects/amenities_images/';
                foreach ($request->file('amenities_images') as $image) {
                    $fileSize = $image->getSize();
                    $fileName = Str::slug($request->slug) . "_" . Str::uuid() . "." . $image->getClientOriginalExtension();
                    $image->move($path, $fileName);

                    FileHelper::uploadFile($path . $fileName, 'amenities_images', ModelType::Project->value, $project->id, [
                        'name' => $request->title,
                        'type' => 'image',
                        'size' => $fileSize,
                    ]);
                }
            }

            // Update Gallery Images
            if ($request->hasFile('gallery')) {
                $oldGallery = $project->gallery;
                foreach ($oldGallery as $file) {
                    FileHelper::deleteFile($file->id);
                }

                $path = 'uploads/projects/gallery/';
                foreach ($request->file('gallery') as $image) {
                    $fileSize = $image->getSize();
                    $fileName = Str::slug($request->slug) . "_" . Str::uuid() . "." . $image->getClientOriginalExtension();
                    $image->move($path, $fileName);

                    FileHelper::uploadFile($path . $fileName, 'gallery', ModelType::Project->value, $project->id, [
                        'name' => $request->title,
                        'type' => 'image',
                        'size' => $fileSize,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Failed to update project. ' . $th->getMessage());
        }

        return redirect()->route('admin.project.list')->with('success', 'Project updated successfully');
    }


    public function delete($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return redirect()->back()->with('error', 'Project not found');
        }



        $files = $project->files;
        // return $project->files;
        foreach ($files as $file) {

            FileHelper::deleteFile($file->id);

        }

        $project->delete();

        return redirect()->route("admin.project.list")->with('success', 'Project deleted successfully');
    }


}
