<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Tag;

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
            'due_at'   => 'nullable|date',
            'priority' => 'required|in:High,Medium,Low,Unlabeled',
            'tag'  =>   'required|in:School,Personal,Others', //halp TT
        ]); //valid title check

        Task::create($validated);

        return redirect('/');
    }

    public function done(Request $request, $id){
        $task = Task::findOrFail($id);
        $task->update(['is_done' => $request->input('is_done')]);

        return redirect('/');
    }

    // edit
    public function edit($id){
        $task = Task::findOrFail($id);
        $tags = Tag::all();
        $priorities = Task:: priorities;
        
        // goes to edit page
        return view('edit', ['task' => $task, 'tags' => $tags, 'priorities' => $priorities]); 
    }

    public function submit($id, Request $request){
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required',
            'priority' => 'required',
            'due_at' => 'nullable',
            'tag_id' => 'nullable',
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
