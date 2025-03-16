<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{

    //location list show or index
    public function index()
    {
        $locations = Location::paginate(20);
        $counter = ($locations->currentPage() - 1) * $locations->perPage();
        foreach ($locations as $index => $location) {
            $location->counter = $counter + $index + 1;
        }
        return view("admin.location.index", compact("locations"));
    }

    //location add page show
    public function add()
    {
        return view("admin.location.location-add");
    }

    //location store
    public function store(Request $request)
    {
        $request->validate([
            "name"=> "required",
            'slug' => 'required|unique:locations,slug'
        ]);

        $location = Location::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
        ]);

        return redirect()->route('admin.location.list')->with('success', 'Location added successfully');
    }

    //location edit page show
    public function edit($id)
    {
        $location = Location::find($id);
        return view("admin.location.location-edit", compact("location"));
    }

    //location update
    public function update(Request $request, $id)
    {

        // return $request;
        $request->validate([
            "name" => "required",
            "slug" => "required|unique:locations,slug,". $id,
        ]);

        $location = Location::find($id);

        if (!$location) {
            return redirect()->back()->with("error", "Location not found");
        }
        try {
            //code...

        $location->name = $request->name;
        $location->slug = Str::slug($request->slug);



        $location->save();

    } catch (\Throwable $th) {
        //throw $th;
        return redirect()->back()->withInput()->with('error', $th->getMessage());
    }


        return redirect()->route('admin.location.list')->with('success', 'Location updated successfully');
    }

    //delete
    public function delete($id)
    {
        $location = Location::find($id);

        if (!$location) {
          return redirect()->back()->error('Location not found');
        }

        $location->delete();

        return redirect()->route('admin.location.list')->with('success', 'Location deleted successfully');

    }

}

