<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TagController;

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

//proof of concept: login/ register/log out
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware(['auth', 'verified'])->group(function () {
Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //----------- TASK ROUTES -------------
    // create tasks
    //change for homepage to have explicit route name


    Route::get('/home', [TaskController::class, 'index'])->name('dashboard')->middleware('auth');
        
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::patch('/tasks/{id}', [TaskController::class, 'update']);

    // edit tasks
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit']);
    Route::patch('/tasks/{id}/submit', [TaskController::class, 'submit']);

    // delete task
    Route::delete('/tasks/{id}/destroy', [TaskController::class, 'destroy']);
    Route::patch('/tasks/{id}/restore', [TaskController::class, 'restore']);




    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
});



require __DIR__.'/auth.php';
