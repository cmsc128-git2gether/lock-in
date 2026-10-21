<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Tag;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Gate};

class GroupTaskController extends Controller
{
    private function rules(): array {
        return [
            'title'     =>  'required|string|max:255',
            'due_at'    =>  'nullable|date',
            'priority'  =>  'required|in:High,Medium,Low,Unlabeled',
            'tag_id'    =>  'required|exists:tags,id',
        ];
    } 

    private function editable(Group $group, Task $task): Task {
        Gate::authorize('view', $group);
        abort_unless($group->tasks()->whereKey($task->id)->exists(), 404);
        abort_unless((int) $task->user_id === (int) auth()->id() || (int) $group->owner_id === (int) auth()->id(), 403);

        return $task;
    }

    public function store(Request $request, Group $group)
    {
        Gate::authorize('view', $group);

        $validated = $request->validate($this->rules()); //valid title check
        
        DB::transaction(function () use ($validated, $request, $group) {
            $task = Task::create($validated + ['user_id' => $request->user()->id]);
            $group->tasks()->attach($task->id);
        });

        return redirect()->route('groups.show', $group);
    }

    public function update(Request $request, Group $group, Task $task){
        Gate::authorize('view', $group);
        abort_unless($group->tasks()->whereKey($task->id)->exists(), 404); // making sure task exist in group

        $task->update(['is_done' => $request->boolean('is_done')]);

        return back();
    }

    // edit by creator
    public function edit(Group $group, Task $task){
        $editableTask = $this->editable($group, $task);
        $tags = Tag::all();
        $priorities = Task::priorities;
        
        // goes to edit page
        return view('groups.tasks.edit', ['group' => $group, 'task' => $editableTask, 'tags' => $tags, 'priorities' => $priorities]); 
    }

    public function submit(Request $request, Group $group, Task $task){
        $this->editable($group, $task)->update($request->validate($this->rules()));

        return redirect()->route('groups.show', $group);
    }

    // delete
    public function destroy(Group $group, Task $task){
        $this->editable($group, $task)->delete();

        return redirect()->route('groups.show', $group)->with('undo_task', $task->id);
    }

    // for undo/restoration
    public function restore(Group $group, $id){
        Gate::authorize('view', $group);

       $task = $group->tasks()
            ->onlyTrashed()
            ->where('tasks.id', $id);

        if ((int) $group->owner_id !== (int) auth()->id()){
            $task->where('tasks.user_id', auth()->id());
        }

        $task->firstOrFail()->restore();

        return redirect()->route('groups.show', $group);
    }
}
