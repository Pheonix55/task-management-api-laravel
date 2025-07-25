<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ProjectController;
use App\Http\Controllers\api\TaskController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// auth
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->group(function () {
    //auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // user
    Route::get('/notifications', [UserController::class, 'myNotifications']);
    Route::post('/notifications/{id}/read', [UserController::class, 'readNotification']);
    Route::post('/notifications/read-all', [UserController::class, 'readAllNotification']);


    //project
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'delete']);
    Route::get('/projects/by/{id}', [ProjectController::class, 'getProjectsByOrganization']);
    Route::get('/project/{id}/report', [ProjectController::class, 'projectReport']);
    Route::get('/project/{id}/top-contributor', [ProjectController::class, 'topContributorProjectWise']);


    // tasks
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    // attachments 
    Route::post('/tasks/{id}/attachments', [TaskController::class, 'addAttachments']);
    Route::get('/tasks/{id}/attachments', [TaskController::class, 'listAttachments']);
    Route::delete('/tasks/{taskId}/attachments/{mediaId}', [TaskController::class, 'destroyAttachment']);

    // comments
    Route::post('/tasks/{id}/comments', [CommentController::class, 'store']);
    Route::get('/tasks/{id}/comments', [CommentController::class, 'index']);

});
