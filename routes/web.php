<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// create tasks
Route::get('/', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::patch('/tasks/{id}', [TaskController::class, 'update']);

// edit tasksz
Route::get('/tasks/{id}/edit', [TaskController::class, 'edit']);
Route::patch('/tasks/{id}/submit', [TaskController::class, 'submit']);

// delete task