<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class ToDoController extends Controller
{
    public function index(){
        $todos = Todo::all();
            return view('index', ['todos' => $todos]);
    }//

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]); //valid title check

        Todo::create($validated);

        return redirect('/');
    }

    public function update($id){
        $todo = Todo::findOrFail($id);
        $todo->update(['is_done' => true]);

        return redirect('/');
    }
}
