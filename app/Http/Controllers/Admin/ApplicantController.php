<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\Career;
use App\Models\Job;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index()
    {
        $applicants = Applicant::paginate(10);
        return view('admin.applicant.index', compact('applicants'));
    }
    public function add(){
        $jobs = Career::all();
        return view('admin.applicant.applicant-add', compact('jobs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required',
            'phone'=> 'required',
            'email'=> 'required',
        ]);
        $applicant = new Applicant();

        if($request->has('name')){
            $applicant->name = $request->input('name');
        };
        if($request->has('phone')){

            $applicant->phone = $request->input('phone');

        };
        if($request->has('email')){

            $applicant->email = $request->input('email');

        };
        if($request->has('subject')){

            $applicant->subject = $request->input('subject');


        };
        if($request->has('message')){

            $applicant->message = $request->input('message');

        };
        if($request->has('cv_link')){

            $applicant->cv_link = $request->input('cv_link');

        };
        if($request->has('job_id')){

            $applicant->job_id = $request->input('job_id');

        };
        if($request->has('status')){

            $applicant->status = $request->input('status');

        };
        $applicant->save();
        return redirect()->route('admin.job.applicant.list')->with('success','Applicant Added Successfully');
    }

    public function edit(Request $request,$id){
        $applicant = Applicant::find($id);
        if(!$applicant){
            return redirect()->route('admin.job.applicant.list')->with('error','Applicant Not Found');
        }
        $jobs = Career::all();

        return view('admin.applicant.applicant-edit',compact('applicant','jobs'));
    }
    public function update(Request $request,$id){
        $request->validate([
            'name'=> 'required',
            'phone'=> 'required',
            'email'=> 'required',
            "subject"=>'required',
        ]);

        $applicant = Applicant::find($id);
        if(! $applicant){
            return redirect()->route('admin.job.applicant.list')->with('error','Applicant Not Found');
        }

        if($request->has('name')){
            $applicant->name = $request->input('name');
        };
        if($request->has('phone')){

            $applicant->phone = $request->input('phone');

        };
        if($request->has('email')){

            $applicant->email = $request->input('email');

        };
        if($request->has('subject')){

            $applicant->subject = $request->input('subject');


        };
        if($request->has('message')){

            $applicant->message = $request->input('message');

        };
        if($request->has('cv_link')){

            $applicant->cv_link = $request->input('cv_link');

        };
        if($request->has('job_id')){

            $applicant->job_id = $request->input('job_id');

        };
        if($request->has('status')){

            $applicant->status = $request->input('status');

        };
        $applicant->save();

        return redirect()->route('admin.job.applicant.list')->with('success','Applicant Updated Successfully');
    }

    public function delete($id)
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->delete();
        return redirect()->route('admin.job.applicant.list')->with('success','Applicant Deleted Successfully');
    }
}
