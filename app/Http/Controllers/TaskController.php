<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\Tag;


class TaskController extends Controller
{
    private function rules(): array {
        return [
            'title'     =>  'required|string|max:255',
            'due_at'    =>  'nullable|date',
            'priority'  =>  'required|in:High,Medium,Low,Unlabeled',
            'tag_id'    =>  'required|exists:tags,id',
        ];
    }

    // task ownership
    private function userTask($id): Task {
        return Task::where('user_id', auth()->id())->findOrFail($id);
    }

    public function index(){
        $tasks = Task::with('tag')
            ->where('user_id', auth()->id())
            ->whereDoesntHave('groups')
            ->orderBy('is_done', 'asc')
            ->orderBy('due_at', 'asc')
            ->orderByRaw('due_at IS NULL')
            ->get();
        
        $tags = Tag::all();
        $priorities = Task:: priorities;

        return view('index', ['tasks' => $tasks, 'tags' => $tags, 'priorities' => $priorities]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_at'   => 'nullable|date',
            'priority' => 'required|in:High,Medium,Low,Unlabeled',
            'tag_id'  =>   'nullable|exists:tags,id',
        ]); //valid title check

        Task::create($validated + ['user_id' => auth()->id()]) ;

        return redirect()->route('dashboard');
    }

    public function update(Request $request, $id){
        // $task = Task::findOrFail($id);
        $this->userTask($id)->update(['is_done' => $request->input('is_done')]);

        return redirect()->route('dashboard');
    }

    // edit
    public function edit($id){
        // $task = Task::findOrFail($id);
        $tags = Tag::all();
        $priorities = Task:: priorities;
        
        // goes to edit page
        return view('edit', ['task' => $this->userTask($id), 'tags' => $tags, 'priorities' => $priorities]); 
    }

    public function submit($id, Request $request){
        // $task = Task::findOrFail($id);
        $this->userTask($id)->update($request->validate($this->rules()));

        return redirect()->route('dashboard');
    }

    // recently deleted
    public function trash(){
        $tasks = Task::onlyTrashed()
            ->with('tag')
            ->where('user_id', auth()->id())
            ->orderBy('deleted_at', 'desc')
            ->get();
        
        return view('trash', ['tasks' => $tasks]);
    }

    // delete
    public function destroy($id){
        $this->userTask($id)->delete();

        return response()->json(['success' => true]);
    }

    // for undo/restoration
    public function restore($id){
       $task = Task::onlyTrashed()
            ->where('user_id', auth()->id())
            ->findOrFail($id);
        $task->restore();

        return request()->expectsJson()
            ? response()->json(['success' => true])
            : back();
    }

    // no undo; permanent deletion
    public function forceDelete($id) {
        $task = Task::withTrashed()
            ->where('user_id', auth()->id())
            ->findOrFail($id);
        $task->forceDelete();

        return request()->expectsJson()
            ? response()->json(['success' => true])
            : back();
    }
}
