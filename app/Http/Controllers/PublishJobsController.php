<?php

namespace App\Http\Controllers;

use App\Models\jobs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PublishJobsController extends Controller
{
    public function index(){
        $jobs = jobs::all(['job_title', 'job_type', 'job_description', 'job_location',
        'company_name']);

        $response = [
            'jobs' => $jobs
        ];
        return response()->json($response, Response::HTTP_OK);
    }
    public function store(Request $request){
        $validation = Validator::make($request->all(), [
            "jobtitle" => "required",
            "jobtype" => "required",
            "jobdescription" => "required",
            "joblocation"=> "required",
            "companyname"=>"required"
        ]);

        // if ($validation->fails()) {
        //     return response()->json(["errors" => $validation->errors()->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
        // }
        jobs::create([
            'job_title' => $request->jobtitle,
            'job_type' => $request->jobtype,
            'job_description' => $request->jobdescription,
            "job_location" => $request->joblocation,
            "company_name"=> $request->companyname,
        ]);

        $response = [
            'job created successfully'
        ];
        return response()->json($response, 201);
    }
    public function applyJobs(){
        
    }
}
