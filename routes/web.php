<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// create tasks
Route::get('/', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::patch('/tasks/{id}', [TaskController::class, 'done']);

// edit tasksz
Route::get('/tasks/{id}/edit', [TaskController::class, 'edit']);
Route::put('/tasks/{id}/update', [TaskController::class, 'update']);

// delete task