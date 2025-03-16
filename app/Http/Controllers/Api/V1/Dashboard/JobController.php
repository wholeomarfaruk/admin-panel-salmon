<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\Career;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function dropCV(Request $request){
        $request->validate([
            'name'=> 'required',
            'phone'=> 'required',
            'email'=> 'required',

        ]);

        $applicant = new Applicant();
        if(!$applicant){
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
            ], 500);
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
        if($request->has('status')){

            $applicant->status = $request->input('status');

        };
        $applicant->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Applicant CV Droped Successfully',
            'data' => $applicant,
        ], 200);
    }

    public function career(){
        $jobs = Career::all();
        return response()->json([
            'status' => 'success',
            'message' => 'careers found',
            'data' => $jobs,
        ], 200);
    }
}
