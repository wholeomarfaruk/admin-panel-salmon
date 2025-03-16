<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Enums\BlogStatus;
use App\Models\Blog;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use App\Http\Requests\BlogRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helper\FileController;
use App\Http\Controllers\helper\MediaController;
use App\Http\Requests\BlogUpdateRequest;

class BlogController extends Controller
{


    // Guest Project Data
    public function blogs(){
        $blogs = Blog::with('thumbnail','gallery','category')->where('status', BlogStatus::PUBLISHED->value)->paginate(10);
        return response()->json([
            'status' => 'success',
            'projects' => $blogs,
        ], 200);
    }

    // Guest Single Project Data
    public function blog($id){
        $project = Blog::with('thumbnail','gallery','category')->where('id', $id)->first();

        if(!$project){
            return response()->json([
                'status' => 'error',
                'code' => 1004,
                'message' => 'Project not found.',
            ], 404);
        }


        return response()->json([
            'status' => 'success',
            'project' => $project,
        ], 200);
    }



    // store
    public function store(BlogRequest $request){

        // Check if the slug already exists
        $existingBlog = Blog::where('slug', $request->slug)->first();
        if ($existingBlog) {
            // Append a unique identifier to the slug
            $slug = $request->slug . '-' . uniqid();
        }

        // Create new blog
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->slug = $slug ?? $request->slug;
        $blog->content = $request->content ?? null;
        $blog->categories = $request->categories ?? null;
        $blog->tags = $request->tags ?? null;
        if($request->hasFile('thumbnail')){
            $imageName = 'uploads/blogs/' . MediaController::uploadFile($request->file('thumbnail'), 'uploads/blogs');
        }
        $blog->thumbnail = $imageName ?? null;
        $blog->save();

        // Get the 'images' files from the request
        $images = $request->file('images');
        if ($images) {
            // Loop through each image
            foreach ($images as $image) {
                FileController::file_upload('uploads/blogs_gallery/' . MediaController::uploadFile($image, 'uploads/blogs_gallery'), $blog->id, $blog->title, 2); // 2 for blog image type in file_upload table of db
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Blog created successfully',
            'data' => $blog
        ], 200);

    }

    // update
    public function update(BlogUpdateRequest $request, $id){

        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json([
                'status' => 'error',
                'message' => 'Blog not found',
                'blog_id' => $id
            ], 404);
        }

        // Check if the slug already exists
        $existingBlog = Blog::where('slug', $request->slug)->where('id', '!=', $id)->first();
        if ($existingBlog) {
            // Append a unique identifier to the slug
            $slug = $request->slug . '-' . uniqid();
        }


        if($request->hasFile('thumbnail')){
            if($blog->thumbnail){
                MediaController::deleteFile($blog->thumbnail);
            }
            $imageName = 'uploads/blogs/' . MediaController::uploadFile($request->file('thumbnail'), 'uploads/blogs');
        }

        $blog->title = $request->title ?? $blog->title;
        if(isset($request->slug)){$blog->slug = $slug ?? $blog->slug;}
        if(isset($request->content)){$blog->content = $request->content ?? $blog->content;}
        if(isset($imageName)){$blog->thumbnail = $imageName ?? $blog->thumbnail;}
        if(isset($request->categories)){$blog->categories = $request->categories ?? $blog->categories;}
        if(isset($request->tags)){$blog->tags = $request->tags ?? $blog->tags;}
        $blog->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog updated successfully',
            'blog_id' => $blog->id
        ], 200);

    }

    // delete
    public function delete($id){

        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'status' => 'error',
                'message' => 'Blog not found'
            ], 404);
        }

        // Gallery delete
        $files = FileUpload::where('reff_id', $id)->get();
        foreach ($files as $file) {
            MediaController::deleteFile($file->file);
            $file->delete();
            $FileController = new FileController();
            $FileController->deleteFile($file->id);

        }

        // Thumbnail delete
        if($blog->thumbnail){
            MediaController::deleteFile($blog->thumbnail);
        }
        $blog->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog deleted successfully'
        ], 200);
    }
}
