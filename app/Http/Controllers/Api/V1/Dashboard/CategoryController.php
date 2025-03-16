<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\helper\MediaController;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function categories(){

        $categories = Category::where('status', 1)->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Categories fetched successfully',
            'data' => $categories
        ], 200);

    }

    public function store(CategoryRequest $request){

        // dd($request->all());


        if($request->hasFile('thumbnail')){
            $imageName = 'uploads/category/' . MediaController::uploadFile($request->file('thumbnail'), 'uploads/category');
        }


        // Check if the slug already exists
        $existingCategory = Category::where('slug', $request->slug)->first();
        if ($existingCategory) {
            $slug = $request->slug . '-' . time();
        }

        $category = Category::create([
            'status' => $request->status ?? 1,
            'name' => $request->name,
            'slug' => $slug ?? $request->slug,
            'thumbnail' => $imageName ?? null,
        ]);
        

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'id' => $category->id
        ], 200);
    
    
    }

    public function update(CategoryRequest $request, $id){

        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }

        if($request->hasFile('thumbnail')){

            if($category->thumbnail){
                MediaController::deleteFile($category->thumbnail);
            }

            $imageName = 'uploads/category/' . MediaController::uploadFile($request->file('thumbnail'), 'uploads/category');
        }

        // Check if the slug already exists
        $existingCategory = Category::where('slug', $request->slug)->where('id', '!=', $id)->first();
        if ($existingCategory) {
            $slug = $request->slug . '-' . time();
        }

        $category->update([
            'status' => $request->status ?? 1,
            'name' => $request->name,
            'slug' => $slug ?? $request->slug,
            'thumbnail' => $imageName ?? $category->thumbnail,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully',
            'id' => $category->id
        ], 200);

    }

    public function delete($id){

        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }

        if($category->thumbnail){
            MediaController::deleteFile($category->thumbnail);
        }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully'
        ], 200);

    }
}
