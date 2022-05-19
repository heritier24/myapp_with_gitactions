<?php

use App\Http\Controllers\applyJobsController;
use App\Http\Controllers\CandidatesController;
use App\Http\Controllers\candidateUsersController;
use App\Http\Controllers\PublishJobsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\ApplicantResultsController;
use App\Models\Exams;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/sign-up', [AuthenticationController::class, 'createAccount']);
    Route::post('/signin', [AuthenticationController::class, 'signin']);
    Route::get('/sign-out', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/listUsers', [AuthenticationController::class, 'listUsers']);
});

Route::get('/jobs', [PublishJobsController::class, 'index']);
Route::post('/publish-jobs', [PublishJobsController::class, 'store']);
Route::post('/apply-jobs', [PublishJobsController::class, 'applyJobs']);
// Route::get('/get-job/{id}', [PublishJobsController::class, 'getJobById']); Not done before
// View applicants
Route::post('/get-all-applicants',[PublishJobsController::class, 'applicantsByJob']);
Route::post('/repply-applicants/{id}',[PublishJobsController::class, 'repplyApplicants']);

Route::post('/login-candidate', [candidateUsersController::class, 'login']);
Route::post('/register-candidate', [candidateUsersController::class, 'registerCandidate']);
Route::get('/logout-candidate', [candidateUsersController::class, 'logout']);

Route::get('/candidates', [CandidatesController::class, 'index']);
Route::post('/record-candidate', [CandidatesController::class, 'registerCandidate']);
Route::put('/update-candidate', [CandidatesController::class, 'updateCandidate']);

// Exams preparation 
Route::post('/prepare-exam',[ExamsController::class,'prepareExam']);
Route::get('/get-exam/{id}',[ExamsController::class, 'getExamForEdit']);
Route::get('/get-indivi-questions/{id}',[ExamsController::class, 'getindividualquestion']);
Route::put('/update-question',[ExamsController::class, 'confirmExamQuestionUpdation']);
Route::delete('/delete-question/{id}',[ExamsController::class, 'deleteSpecificQuestion']);
Route::put('/mark-exam-expired/{id}',[ExamsController::class,'markExamAsExpired']);
Route::post('/set-exam-period',[ExamsController::class,'setExamPeriod']);

// Candididate results 
Route:: post('get-application-result',[ApplicantResultsController::class,'getApplicationResults']);
Route::post('/get-exam-to-do',[ApplicantResultsController::class, 'getExamTodo']);
Route::post('do-exam/{id}',[ApplicantResultsController::class, 'doExam']);
Route::post('/submit-answer',[ApplicantResultsController::class, 'submitSxamResults']);
