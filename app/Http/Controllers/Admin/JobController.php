<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Career::paginate(10);
        return view('admin.job.index', compact('jobs'));
    }
    public function add(Request $request)
    {
        return view('admin.job.job-add');
    }
    public function store(Request $request)
    {
        // return $request->all();
        $request->validate([
            "title"=> "required",

            'status'=> 'required',
            'requirements'=> 'required',
        ]);
        $job = new Career();
        $job->title = $request->title;
        $slug = Str::slug($request->title);
        if(Career::where('slug', $slug)->exists()){
            $slug = $slug.'-'.uniqid();
        }
        $job->slug = $slug;
        $job->description = $request->description ?? '';
        $job->status = $request->status;
        $job->requirements = json_encode($request->requirements);
        $job->save();
        return redirect()->route('admin.job.list')->with('success','Job Added Successfully');
    }

    //edit
    public function edit(Request $request,$id){
        $job = Career::find($id);
        if(!$job){
            return redirect()->route('admin.job.list')->with('error','Job Not Found');
        }
        return view('admin.job.job-edit',compact('job'));
    }
    public function update(Request $request,$id){
        $job = Career::find($id);
        if(!$job){
            return redirect()->route('admin.job.list')->with('error','Job Not Found');
        }
        $request->validate([
            "title"=> "required",

            'status'=> 'required',
            'requirements'=> 'required',
        ]);
        $job->title = $request->title;
        $job->description = $request->description ?? '';
        $job->status = $request->status;
        $job->requirements = json_encode($request->requirements);
        $job->save();
        return redirect()->route('admin.job.list')->with('success','Job Updated Successfully');
    }

    public function delete($id){
        $job = Career::find($id);
        if(!$job){
            return redirect()->route('admin.job.list')->with('error','Job Not Found');
        }

        $job->delete();
        return redirect()->route('admin.job.list')->with('success','Job Deleted Successfully');
    }
}
