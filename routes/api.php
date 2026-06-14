<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TestCaseController;
use App\Http\Controllers\TestExecutionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BugController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\ModuleController;

// Public Route
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected Routes (Harus Login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::apiResource('projects', ProjectController::class);
    Route::get('projects/{id}/report', [ProjectController::class, 'getReportData']);
    
    Route::apiResource('users', UserController::class);
    Route::apiResource('test-cases', TestCaseController::class);
    Route::post('test-cases/generate-ai', [TestCaseController::class, 'generateWithAI']);
    
    Route::get('executions', [TestExecutionController::class, 'index']);
    Route::post('executions', [TestExecutionController::class, 'store']);
    
    Route::get('/bugs', [BugController::class, 'index']);
    Route::post('bugs/report', [BugController::class, 'report']);
    Route::post('bugs/sync', [BugController::class, 'sync']);

    // Knowledge Base Routes
    Route::get('knowledge-base', [KnowledgeBaseController::class, 'index']);
    Route::get('knowledge-base/show', [KnowledgeBaseController::class, 'show']);
    Route::post('knowledge-base/upload', [KnowledgeBaseController::class, 'upload']);
    Route::delete('knowledge-base', [KnowledgeBaseController::class, 'destroy']);
    Route::post('knowledge-base/clear-cache', [KnowledgeBaseController::class, 'clearCache']);

    // Module Routes
    Route::get('projects/{projectId}/modules', [ModuleController::class, 'index']);
    Route::post('projects/{projectId}/modules', [ModuleController::class, 'store']);
    Route::put('modules/{id}', [ModuleController::class, 'update']);
    Route::delete('modules/{id}', [ModuleController::class, 'destroy']);
    Route::get('modules/{moduleId}/test-cases', [ModuleController::class, 'testCases']);
});

Route::middleware('auth:sanctum')->get('/me', function () {
    return response()->json([
        'user' => auth()->user()
    ]);
});
