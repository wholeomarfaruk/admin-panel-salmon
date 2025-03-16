<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ProjectStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectStatusController extends Controller
{

    //projectstatus list show or index
    public function index()
    {
        $project_statuses = ProjectStatus::paginate(2);
        $counter = ($project_statuses->currentPage() - 1) * $project_statuses->perPage();
        foreach ($project_statuses as $index => $project_status) {
            $project_status->counter = $counter + $index + 1;
        }

        return view("admin.project_status.index", compact("project_statuses"));
    }

    //project_status add page show
    public function add()
    {
        return view("admin.project_status.project_status-add");
    }

    //project_status store
    public function store(Request $request)
    {
        $request->validate([
            "name"=> "required",
            'slug' => 'required|unique:project_statuses,slug'
        ]);

        $project_status = ProjectStatus::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
        ]);

        return redirect()->route('admin.project_status.list')->with('success', 'project_status added successfully');
    }

    //project_status edit page show
    public function edit($id)
    {
        $project_status = ProjectStatus::find($id);
        return view("admin.project_status.project_status-edit", compact("project_status"));
    }

    //project_status update
    public function update(Request $request, $id)
    {

        // return $request;
        $request->validate([
            "name" => "required",
            "slug" => "required|unique:project_statuses,slug,". $id,
        ]);

        $project_status = ProjectStatus::find($id);

        if (!$project_status) {
            return redirect()->back()->with("error", "project_status not found");
        }
        try {
            //code...

        $project_status->name = $request->name;
        $project_status->slug = Str::slug($request->slug);



        $project_status->save();

    } catch (\Throwable $th) {
        //throw $th;
        return redirect()->back()->withInput()->with('error', $th->getMessage());
    }


        return redirect()->route('admin.project_status.list')->with('success', 'project_status updated successfully');
    }

    //delete
    public function delete($id)
    {
        $project_status = ProjectStatus::find($id);

        if (!$project_status) {
          return redirect()->back()->error('project_status not found');
        }

        $project_status->delete();

        return redirect()->route('admin.project_status.list')->with('success', 'project_status deleted successfully');

    }

}

