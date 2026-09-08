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
        $todo = Task::findOrFail($id);
        $todo->update(['is_done' => true]);

        return redirect('/');
    }
}
