<?php

namespace App\Http\Controllers;

use App\Models\applyjob;
use App\Models\jobs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PublishJobsController extends Controller
{

    // list all jobs
    public function index()
    {
        $jobs = DB::table('jobs')
            ->select(
                'id',
                'job_title',
                'job_type',
                'job_description',
                'job_location',
                'company_name'
            )->where('exam_status', 'On to dos list')
            ->get();

        $response = [
            'jobs' => $jobs
        ];
        return response()->json($response, Response::HTTP_OK);
    }
    // publish jobs
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "jobtitle" => "required",
            "jobtype" => "required",
            "jobdescription" => "required",
            "joblocation" => "required",
            "companyname" => "required"
        ]);

        if ($validation->fails()) {
            return response()->json(["errors" => $validation->errors()->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        jobs::create([
            'job_title' => $request->jobtitle,
            'job_type' => $request->jobtype,
            'job_description' => $request->jobdescription,
            "job_location" => $request->joblocation,
            "company_name" => $request->companyname,
        ]);

        $response = [
            'job created successfully'
        ];
        return response()->json($response, 201);
    }
    // applying for a job
    public function applyJobs(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "candidateid" => "required",
            "date_applied" => "required",
            // "status" => "required"
        ]);

        if ($validation->fails()) {
            return response()->json(["errors" => $validation->errors()->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        applyjob::create([
            'candidateid' => $request->candidateid,
            'jobid' => $request->jobsid,
            'date_applied' => $request->date_applied,
            // "status" => $request->status
        ]);

        $response = [
            'applying jobs created successfully'
        ];
        return response()->json($response, 201);
    }

    // view applied for a jobs 
    public function applied()
    {
        // $applied = DB::select('SELECT candidates.candidate_names,jobs.job_title,jobs.companyname,
        //                        applyjobs.date_applied,applyjobs.status FROM applyjobs
        //                        INNER JOIN jobs ON ');
    }

    // Get applicants based on job applied for 
    public function applicantsByJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $get_applicants = DB::table('applyjobs')
            ->join('jobs', 'jobs.id', 'applyjobs.jobid')
            ->join('candidates', 'candidates.id', 'applyjobs.candidateid')
            ->join('candidateusers', 'candidateusers.id', 'candidates.candidate_userid')
            ->select('applyjobs.id', 'applyjobs.status', 'candidateusers.name', 'candidateusers.email', 'candidates.candidate_phonenumber', 'candidates.nationalid', 'jobs.job_title', 'jobs.job_type', 'jobs.company_name')
            ->where('jobs.id', $request->job_id)
            ->where('applyjobs.status', 'Pending')
            ->get();

        return response()->json([
            "status" => 201,
            "result" => $get_applicants
        ]);
    }

    // Repply applicant 
    public function repplyApplicants(Request $request, $application_id)
    {
        $validation = Validator::make($request->all(), [
            'response' => 'required'
        ]);
        if ($validation->fails()) {
            return response()->json(["Errors" => $validation->errors()->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $update_to_repply = applyjob::where('id', $application_id)->update([
            "status" => $request->response,
        ]);
        if($update_to_repply){
            return response()->json([
                'status'=>201,
                'result'=>"Response successfully sent to applicant"
            ]);
        }
    }
}
