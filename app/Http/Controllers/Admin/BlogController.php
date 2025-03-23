<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ModelType;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helper\FileController;
use App\Http\Controllers\helper\MediaController;
use App\Http\Requests\BlogRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{

    //index or list
    public function index()
    {
        $blogs = Blog::orderBy("created_at", "desc")->paginate(10);
        // return $blogs;
        return view("admin.blog.index", compact("blogs"));
    }

    // add page show
    public function add()
    {
        $categories = Category::all();
        return view("admin.blog.blog-add", compact("categories"));
    }

    // store
    public function store(Request $request)
    {
        $request->validate([
            "title"=> "required",
            "status"=> "required",
            'is_featured'=> 'required',

            "content"=> "required",

        ]);


        $slug = Str::slug($request->title);
        // Check if the slug already exists
        if($request->has("slug")){
            $slug = $request->slug;
        }
        $existingBlog = Blog::where('slug', $slug)->first();
        if ($existingBlog) {
            // Append a unique identifier to the slug
            $slug .=  '-' ;
        }

        // Create new blog
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->slug = $slug ?? Str::slug($request->title);
        $blog->content = $request->content ?? null;
        $blog->category_id = $request->categories ?? null;
        $blog->tags = $request->tags ?? null;
        $blog->is_featured= $request->is_featured ?? 0;

        $blog->save();

        if ($request->hasFile('thumbnail')) {
            $path = 'uploads/blogs/thumbnails/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $fileSize = $request->file('thumbnail')->getSize(); // Get size before moving
            $imageRename = Str::slug($request->title) . "_" . Str::uuid() . "_size_." . $request->file('thumbnail')->getClientOriginalExtension();
            $request->file('thumbnail')->move($path, $imageRename);

            FileHelper::uploadFile($path . $imageRename, 'thumbnail', ModelType::Blog->value, $blog->id, [
                'name' => $request->title,
                'type' => 'image',
                'size' => $fileSize,
            ]);
        }

        if ($request->hasFile('gallery')) {
            $path = 'uploads/blogs/gallery/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            foreach ($request->file('gallery') as $slider) {


                $fileSize = $slider->getSize();
                $imageRename = Str::slug($request->title) . "_" . Str::uuid() . "_size_." . $slider->getClientOriginalExtension();
                $slider->move($path, $imageRename);

                $path . $imageRename;
                FileHelper::uploadFile($path . $imageRename, 'gallery', ModelType::Blog->value, $blog->id, [
                    'name' => $request->title,
                    'type' => 'image',
                    'size' => $fileSize,

                ]);
            }
        }



        return redirect()->route("admin.blog.list")->with("success", "Blog added successfully");

    }

    //edit
    public function edit($id)
    {
        $blog = Blog::find($id);
        $categories = Category::all();
        return view("admin.blog.blog-edit", compact("blog", "categories"));
    }

    // update
    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'status'=> 'required',
            'is_featured'=> 'required',
        ]);

        // Check if the slug already exists
        $blog = Blog::find($id);
        if (!$blog) {
            return redirect()->route("admin.blog.list")->with("error", "Blog not found");
        }


        try {
            //code...



            $blog->title = $request->title ?? $blog->title;

                  $slug = Str::slug($request->title);
                    // Check if the slug already exists
                    if($request->has("slug")){
                        $slug = $request->slug;
                    }
                if(Blog::where('slug', $slug)->where('id', '!=', $id)->exists()){
                    $slug = $slug.'-';
                }

                $blog->slug = $slug;

            if (isset($request->content)) {
                $blog->content = $request->content ?? $blog->content;
            }

            if (isset($request->categories)) {
                $blog->category_id = $request->categories ?? $blog->categories;
            }

            if (isset($request->is_featured)) {
                $blog->is_featured = $request->is_featured ?? $blog->is_featured;
            }


                $blog->tags = $request->tags;
                // return request()->all();

            if (isset($request->status)) {
                $blog->status = $request->status ?? $blog->status;
            }
            // Delete old images which are not in the new images list

            $blog->save();

            // Update Thumbnail
            if ($request->hasFile('thumbnail')) {
                $oldThumbnail = $blog->thumbnail;
                if ($oldThumbnail) {
                    FileHelper::deleteFile($oldThumbnail->id);
                }

                $path = 'uploads/blogs/thumbnails/';
                $fileSize = $request->file('thumbnail')->getSize();
                $fileName = Str::slug($request->title) . "_" . Str::uuid() . "." . $request->file('thumbnail')->getClientOriginalExtension();
                $request->file('thumbnail')->move($path, $fileName);

                FileHelper::uploadFile($path . $fileName, 'thumbnail', ModelType::Blog->value, $blog->id, [
                    'name' => $request->title,
                    'type' => 'image',
                    'size' => $fileSize,
                ]);
            }

            // Update Gallery Images
            if ($request->hasFile('gallery')) {
                $oldGallery = $blog->gallery;
                foreach ($oldGallery as $file) {
                    FileHelper::deleteFile($file->id);
                }

                $path = 'uploads/blogs/gallery/';
                foreach ($request->file('gallery') as $image) {
                    $fileSize = $image->getSize();
                    $fileName = Str::slug($request->title) . "_" . Str::uuid() . "." . $image->getClientOriginalExtension();
                    $image->move($path, $fileName);

                    FileHelper::uploadFile($path . $fileName, 'gallery', ModelType::Blog->value, $blog->id, [
                        'name' => $request->title,
                        'type' => 'image',
                        'size' => $fileSize,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withInput()->with("error", $th->getMessage());
        }
        return redirect()->route("admin.blog.list")->with("success", "Blog updated successfully");

    }

    // delete
    public function delete($id)
    {

        $project = Blog::find($id);

        if (!$project) {
            return redirect()->back()->with('error', 'Blog not found');
        }




        $files = $project->files;
        // return $project->files;
        foreach ($files as $file) {
            FileHelper::deleteFile($file->id);
        }

        $project->delete();

        return redirect()->route("admin.blog.list")->with('success', 'Blog deleted successfully');
    }

    public function storeFiles(Request $request)
    {
        $path = public_path('uploads/blogs_gallery/');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);
        $name = 'uploads/blogs_gallery/' . $name;
        return response()->json([
            'name' => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
}
