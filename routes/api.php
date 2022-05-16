<?php

use App\Http\Controllers\candidateUsersController;
use App\Http\Controllers\PublishJobsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/get-job/{id}', [PublishJobsController::class, 'getJobById']);

Route::get('/candidate', [candidateUsersController::class, 'index']);
Route::post('/register-candidate', [candidateUsersController::class, 'registerCandidate']);

