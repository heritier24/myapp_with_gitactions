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
            "jobsid" => "required",
            // "status" => "required"
        ]);

        if ($validation->fails()) {
            return response()->json(["errors" => $validation->errors()->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // Check if candidates already applied 
        $check_candidate = DB::table('jobs')
        ->join('applyjobs','applyjobs.jobid','jobs.id')
        ->join('candidates', 'candidates.id', 'applyjobs.candidateid')
        ->join('candidateusers', 'candidateusers.id', 'candidates.candidate_userid')
        ->where('applyjobs.jobid',$request->jobsid)
        ->where('candidateusers.id',$request->candidateid)
        ->count();

        if($check_candidate){
            return response()->json([
                'errors'=>"You have already applied for this job"
            ], 500);
        }
        applyjob::create([
            'candidateid' => $request->candidateid,
            'jobid' => $request->jobsid,
            'date_applied' => date('Y-m-d'),
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
    public function applicantsByJob($jobid)
    {

        $get_applicants = DB::table('applyjobs')
            ->join('jobs', 'jobs.id', 'applyjobs.jobid')
            ->join('candidates', 'candidates.id', 'applyjobs.candidateid')
            ->join('candidateusers', 'candidateusers.id', 'candidates.candidate_userid')
            ->select('applyjobs.id AS applyjobid', 'applyjobs.status', 'candidates.candidate_names',
             'candidates.candidate_email', 'candidates.candidate_phonenumber', 
             'candidates.nationalid', 'jobs.job_title', 'jobs.job_type', 'jobs.company_name')
            ->where('jobs.id', $jobid)
            ->where('applyjobs.status', 'Pending')
            ->get();

        return response()->json([
            "status" => 201,
            "result" => $get_applicants
        ]);
    }

    // Repply applicant 
    public function repplyApplicants(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'response' => 'required'
        ]);
        if ($validation->fails()) {
            return response()->json(["Errors" => $validation->errors()->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $update_to_repply = applyjob::where('id', $request->idjob)->update([
            "status" => $request->response,
        ]);
        if($update_to_repply){
            return response()->json([
                'result'=>"Response successfully sent to applicant"
            ], 201);
        }
    }
}
