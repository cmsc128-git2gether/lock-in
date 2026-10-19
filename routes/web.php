<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

//proof of concept: login/ register/log out
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    //----------- TASK ROUTES -------------
    //change for homepage to have explicit route name
    Route::get('/home', [TaskController::class, 'index'])->name('dashboard')->middleware('auth');
        
    // create and update done toggle
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::patch('/tasks/{id}', [TaskController::class, 'update']);

    // edit tasks
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit']);
    Route::patch('/tasks/{id}/submit', [TaskController::class, 'submit']);

    // delete, undo task, recently deleted
    Route::get('/tasks/trash', [TaskController::class, 'trash']);
    Route::delete('/tasks/{id}/destroy', [TaskController::class, 'destroy']);
    Route::patch('/tasks/{id}/restore', [TaskController::class, 'restore']);
    Route::delete('/tasks/{id}/force', [TaskController::class, 'forceDelete']);

});



require __DIR__.'/auth.php';
