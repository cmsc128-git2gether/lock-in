<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(){
        $tasks = Task::all();
            return view('index', ['tasks' => $tasks]);
    }//

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]); //valid title check

        Task::create($validated);

        return redirect('/');
    }

    public function done($id){
        $task = Task::findOrFail($id);
        $task->done(['is_done' => true]);

        return redirect('/');
    }

    // edit
    public function edit($id){
        $task = Task::findOrFail($id);
        // goes to edit page
        return view('edit', ['task' => $task]); 
    }

    public function update(Task $task, Request $request){
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'priority' => 'required',
            'due_at' => 'nullable',
        ]); //validate

        Task::update($validated);

        return redirect('/');
    }

    // delete
    public function destroy(Task $task){
        $task->delete();

        return redirect('/');
    }

    // for undo/restoration
    public function restore($id){
        $task = Task::onlyTrashed()->findOrFail($id);
        $task = restore();

        return redirect('/');
    }

    
}
