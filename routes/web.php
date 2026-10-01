<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

//proof of concept: login/ register/log out
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//----------- TASK ROUTES -------------
// create tasks
//change for homepage to have explicit route name
//added middleware to all routes


Route::middleware('auth')->group(function () {
    Route::get('/home', [TaskController::class, 'index'])->name('dashboard');

    Route::post('/tasks', [TaskController::class, 'store']);
    Route::patch('/tasks/{id}', [TaskController::class, 'update']);
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit']);
    Route::patch('/tasks/{id}/submit', [TaskController::class, 'submit']);
    Route::delete('/tasks/{id}/destroy', [TaskController::class, 'destroy']);
    Route::patch('/tasks/{id}/restore', [TaskController::class, 'restore']);
});

require __DIR__.'/auth.php';
