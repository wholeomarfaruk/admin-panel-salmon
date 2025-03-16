<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

    /**
     * Show the categories index page Start.================================================
     */
    public function index()
    {
        $categories = Category::paginate(20);
        return view('admin.category.index', compact('categories'));
    }
    /**
     * Show the categories index page End.================================================
     */

    /**
     * Show the categories add page Start.================================================
     */
    public function add()
    {
        return view('admin.category.category-add');
    }
    /**
     * Show the categories add page end.================================================
     */


    /**
     * Store a newly created resource in storage Start.================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required',
            'status'=> 'required',
        ]);

        // return $request->all();

        // Check if the slug already exists
        $slug = Str::slug($request->name);
        if(Category::where('slug', $slug)->exists()){
            $slug .= '-';
        }

        $category = Category::create([
            'status' => $request->status ?? 1,
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.category.list')->with('success', 'Category added successfully');
    }
    /**
     * Store a newly created resource in storage End.================================================
     */


    /**
     * Show the form for editing the specified category.================================================
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */

    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.category.category-edit', compact('category'));
    }

    /**
     * Show the form for editing the specified category End.================================================
     */


    // Update the specified category in storage Start.================================================

    // @param  \App\Http\Requests\CategoryRequest  $request
    // @param  int  $id
    // @return \Illuminate\Http\Response

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=> 'required',
            'status'=> 'required',
        ]);
        $category = Category::find($id);

        if (!$category) {
            return redirect()->back()->with('error', 'Category not found');
        }

        // Check if the slug already exists
        $existingCategory = Category::where('slug', $request->slug)->where('id', '!=', $id)->first();
        if ($existingCategory) {
            $slug = $request->slug . '-' . time();
        }
        // Check if the slug already exists
        $slug = Str::slug($request->name);
        if(Category::where('slug', $slug)->where('id', '!=', $id)->exists()){
            $slug .= '-';
        }

        $category->update([
            'status' => $request->status ?? 1,
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.category.list')->with('success', 'Category updated successfully');

    }
    // Update the specified category in storage End.================================================

    // Remove the specified category from storage Start.================================================
    public function delete($id)
    {

        $category = Category::find($id);

        if (!$category) {
            return redirect()->back()->with('error', 'Category not found');
        }

        $category->delete();

        return redirect()->route('admin.category.list')->with('success', 'Category deleted successfully');

    }
    // Remove the specified category from storage End.================================================


}

