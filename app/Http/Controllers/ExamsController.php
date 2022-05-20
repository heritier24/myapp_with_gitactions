<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Models\Exams;
use Illuminate\Support\Facades\DB;
use App\Models\ReadyExam;
use App\Models\jobs;

class ExamsController extends Controller
{
    // Get all prepared exam based to its status 
    public function getindividualquestion($individualQuestion)
    {
        $prepared_questions_individual = DB::table('exams')
            ->where('id', $individualQuestion)
            ->get();

        return response()->json([
            'status' => 201,
            'result' => $prepared_questions_individual
        ], Response::HTTP_OK);
    }
    // Prepare exam based on posted job /
    public function prepareExam(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "job_id" => "required",
            "question" => "required",
            "answer" => "required",
            "mark_precized" => "required|numeric"
        ]);

        if ($validation->fails()) {
            return response()->json([
                "errors" => $validation->errors()->all(),
                'jobid' => $request->job_id
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $exam = Exams::create([
            'job_id' => $request->job_id,
            'question' => $request->question,
            'option_1' => $request->option_1,
            'option_2' => $request->option_2,
            'option_3' => $request->option_3,
            'option_4' => $request->option_4,
            'option_5' => $request->option_5,
            'option_6' => $request->option_6,
            'answer' => $request->answer,
            'mark_precized' => $request->mark_precized
        ]);

        return \response()->json([
            "status" => 200,
            'result' => $exam
        ], Response::HTTP_OK);
    }

    // Edit prepared exam 
    public function getExamForEdit($exanTitle)
    {
        $prepared_questions = DB::table('exams')
            ->join('jobs', 'jobs.id', 'exams.job_id')
            ->select('exams.*', 'jobs.job_title', 'jobs.company_name')
            ->where('exams.job_id', $exanTitle)
            ->where('exams.exam_status', "On to dos list")
            ->get();

        return response()->json([
            'status' => 200,
            'result' => $prepared_questions
        ], Response::HTTP_OK);
    }

    // Confirm changes 
    public function confirmExamQuestionUpdation(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "question" => "required",
            "answer" => "required",
            "mark_precized" => "required|numeric"
        ]);

        if ($validation->fails()) {
            return response()->json([
                "errors" => $validation->errors()->all(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $update_quesrtion = Exams::where('id', $request->question_id)->update([
            "job_id" => $request->job_id,
            "question" => $request->question,
            "answer" => $request->answer,
            "mark_precized" => $request->mark_precized
        ]);
        if ($update_quesrtion) {
            return response()->json([
                'status' => 200,
                'result' => "Question updated successfully",
            ], Response::HTTP_OK);
        }
        return response()->json([
            "errors" => $update_quesrtion->errors()->all(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // Delete a specific question 
    public function deleteSpecificQuestion($selectedQuestion)
    {
        $deleteQuestion = Exams::find($selectedQuestion)->delete();
        if ($deleteQuestion) {
            $response = [
                "Successfully Deleted"
            ];
            return response()->json([$response, 201], Response::HTTP_OK);
        }
        return response()->json([
            "errors" => $deleteQuestion->errors()->all(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function markJobAsExpired($jobPosted)
    {
        jobs::where('id', $jobPosted)->update([
            "exam_status" => 'Period expired',
        ]);
        return response()->json([
            'status' => 201,
            'result' => "Job post expiration setted successfully"
        ]);
    }

    public function setExamPeriod(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'job_id' => 'required',
            'start_at' => 'required',
            'end_at' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(["Error" => $validation->errors()->all()], 500);
        }

        readyExam::create([
            'job_id' => $request->job_id,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at
        ]);
        return response()->json([
            "status" => 201,
            "result" => "The exam period successuflly created"
        ]);
    }
}
