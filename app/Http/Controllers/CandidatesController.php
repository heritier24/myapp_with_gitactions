<?php

namespace App\Http\Controllers;

use App\Models\candidates;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CandidatesController extends Controller
{
    public function index()
    {
        $candidates = candidates::all([
            'id', 'candidate_names', 'candidate_phonenumber',
            'candidate_email', 'nationalid', 'cv'
        ]);

        $response = [
            'candidates' => $candidates
        ];
        return response()->json($response, Response::HTTP_OK);
    }
    public function registerCandidate(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "candidate_userid" => "required",
            "candidate_names" => "required",
            "phonenumber" => "required",
            "email" => "required",
            "nationalid" => "required",
            "cv" => "required"
        ]);
        if ($validation->fails()) {
            return response()->json(["errors" => $validation->errors()->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $candidateid = candidates::find($request->candidate_userid);
        if ($candidateid === null) {
            return response()->json(['error' => ["create user account first to register the document "]], Response::HTTP_NOT_FOUND);
        }
        candidates::create([
            'candidate_userid' => $request->candidate_userid,
            'candidate_names' => $request->candidate_names,
            'phonenumber' => $request->phonenumber,
            "email" => $request->email,
            "nationalid" => $request->nationalid,
            "cv" => $request->cv
        ]);

        $response = [
            'successfully candidate registered'
        ];
        return response()->json($response, 201);
    }
    public function updateCandidate(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            "candidate_names" => "required",
            "phonenumber" => "required",
            "email" => "required",
            "nationalid" => "required",
            "cv" => "required"
        ]);
        if ($validation->fails()) {
            return response()->json(["errors" => $validation->errors()->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        candidates::where('id', $id)->update([
            'candidate_names' => $request->candidate_names,
            'phonenumber' => $request->phonenumber,
            "email" => $request->email,
            "nationalid" => $request->nationalid,
            "cv" => $request->cv
        ]);

        $response = [
            'successfully candidate registered'
        ];
        return response()->json($response, 201);
    }
}
