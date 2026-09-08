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

    public function update($id){
        $task = Task::findOrFail($id);
        $task->update(['is_done' => true]);

        return redirect('/');
    }

    // edit
    public function edit($id){
        $task = Task::findOrFail($id);
        // goes to edit page
        return view('edit', ['task' => $task]); 
    }

    public function submit($id, Request $request){
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'priority' => 'required',
            'due_at' => 'nullable',
        ]); //validate

        $task->update($validated);

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
