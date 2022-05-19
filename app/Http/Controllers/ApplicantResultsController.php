<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\CandidatesAnswers;

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

    public function submitExamResults(Request $request)
    {
        $submitted_answers = array([
            "question_id" => $request->question_id,
            "answer" => $request->answer
        ]);
        $candidate_id = $request->cand_id;

        // Get the questions with answers
        foreach ($submitted_answers as $answers) {
            $question_list = DB::table('exams')
                ->where('id', $answers['question_id'])
                ->get();
            foreach ($question_list as $results) {
                if ($answers['answer'] === $results->answer) {
                    CandidatesAnswers::create([
                        'question_id'=> $answers['question_id'],
                        'candidate_id'=> $candidate_id,
                        'candidate_answer'=> $answers['answer'],
                        'marks'=> $results->mark_precized,
                        'answer_status'=>true
                    ]);
                    return response()->json([
                        'status' => 201,
                        'result' => "The answers submited well, check the result now.",
                       
                    ]);
                }
                CandidatesAnswers::create([
                    'question_id' => $answers['question_id'],
                    'candidate_id' => $candidate_id,
                    'candidate_answer' => $answers['answer'],
                    'marks' => 0,
                    'answer_status' => false
                ]);
                return response()->json([
                    'status' => 201,
                    'result' => "The answers submited well, check the result now.",
                    
                ]);
            }
        }
    }

    // Get results of sumitted ansewers
    public function getResultsOfAnswers(Request $request){
        $gotten_results = DB::table('candidates_answers')
        ->join('exams', 'exams.id', 'candidates_answers.question_id')
        ->join('candidates', 'candidates.id', 'candidates_answers.candidate_id')
        ->join('applyjobs', 'applyjobs.candidateid', 'candidates.id')
        ->join('jobs', 'jobs.id', 'applyjobs.jobid')
        ->join('candidateusers', 'candidateusers.id', 'candidates.candidate_userid')
        ->select('candidates_answers.candidate_id', 'candidateusers.name', 'jobs.job_title', 'jobs.job_type')
        ->where('candidates.id',$request->cand_id)
        ->first();

        // foreach($gotten_results as $results){
            $real_cand_id = $gotten_results->candidate_id;
        // }
        $tota_gotten_marks = DB::table('candidates_answers')          
            ->where('candidate_id', $real_cand_id)
            ->sum('marks');
        return response()->json([
            'status'=>201,
            'result'=> $gotten_results,
            'marks'=> $tota_gotten_marks

        ]);
    }
}
