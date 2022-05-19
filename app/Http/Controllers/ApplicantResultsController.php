<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApplicantResultsController extends Controller
{
    // View application result
    public function getApplicationResults(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        if ($validation->fails()) {
            return response()->json(["Error" => $validation->errors()->all()], 500);
        }
        $application_result = DB::table('applyjobs')
            ->join('jobs', 'jobs.id', 'applyjobs.jobid')
            ->join('candidates', 'candidates.id', 'applyjobs.candidateid')
            ->join('candidateusers', 'candidateusers.id', 'candidates.candidate_userid')
            ->where('candidateusers.id', $request->user_id)
            ->get();

        return response()->json([
            'status' => 201,
            'result' => $application_result
        ]);
    }

    // Get exam when shortlisted 
    public function getExamTodo(Request $request)
    {

        $exams_on_list = DB::table('applyjobs')
            ->join('jobs', 'jobs.id', 'applyjobs.jobid')
            ->join('candidates', 'candidates.id', 'applyjobs.candidateid')
            ->join('candidateusers', 'candidateusers.id', 'candidates.candidate_userid')
            ->select('applyjobs.id', 'applyjobs.status', 'candidateusers.name', 'candidateusers.email', 'candidates.candidate_phonenumber', 'candidates.nationalid', 'jobs.job_title', 'jobs.job_type', 'jobs.company_name')
            ->where('jobs.id', $request->user_id)
            ->where('applyjobs.status', 'Short listed')
            ->where('jobs.exam_status', 'On to dos list')
            ->get();

        return response()->json([
            'status' => 201,
            'result' => $exams_on_list
        ]);
    }
    public function doExam(Request $request, $job_id)
    {
        $doExam = DB::table('jobs')
            ->join('exams', 'exams.job_id', 'jobs.id')
            ->join('ready_exams', 'ready_exams.job_id', 'jobs.id')
            ->where('jobs.id', $job_id)
            ->get();

        return response()->json([
            'status' => 201,
            'result' => $doExam
        ]);
    }

    public function submitSxamResults(Request $request)
    {
        $submitted_answers = array([            
            "question_id" => $request->question_id,
            "answer" =>$request->answer
        ]);

        return response()->json([
            'status'=>201,
            'result'=>$submitted_answers
        ]);
    }
}
