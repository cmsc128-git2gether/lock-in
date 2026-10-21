<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\GroupTaskController;

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

    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');

     // GROUP ROUTES
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');

    // GROUP MEMBER ROUTES
    Route::post('/groups/{group}/members', [GroupMemberController::class, 'store'])->middleware('throttle:20,1')->name('groups.members.store');
    Route::delete('/groups/{group}/members/{user}', [GroupMemberController::class, 'destroy'])->name('groups.members.destroy');

    // GROUP TASK ROUTES
    // create group task
    Route::post('/groups/{group}/tasks', [GroupTaskController::class, 'store'])->name('groups.tasks.store');
    
    // toggle done group task
    Route::patch('/groups/{group}/tasks/{task}', [GroupTaskController::class, 'update'])->name('groups.tasks.update');
    
    // edit group task
    Route::get('/groups/{group}/tasks/{task}/edit', [GroupTaskController::class, 'edit'])->name('groups.tasks.edit');
    Route::patch('/groups/{group}/tasks/{task}/submit', [GroupTaskController::class, 'submit'])->name('groups.tasks.submit');

    // delete group task
    Route::delete('/groups/{group}/tasks/{task}', [GroupTaskController::class, 'destroy'])->name('groups.tasks.destroy');
    Route::patch('/groups/{group}/tasks/{task}/restore', [GroupTaskController::class, 'restore'])->name('groups.tasks.restore');
});


require __DIR__.'/auth.php';
