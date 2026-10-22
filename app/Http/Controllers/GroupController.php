<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Task;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Gate};

class GroupController extends Controller
{
    // main view of groups
    public function index() {
        $groups = auth()->user()->groups()
            ->withCount('members')->with('owner')
            ->orderBy('groups.name', 'asc')->get();

        return view('groups.index', ['groups' => $groups]);
    }

    // create group
    // public function store(Request $request) {
    //     $info = $request->validate(['name' => 'required|string|max:255']);

    //     $group = DB::transaction(function () use ($info, $request) {
    //         $group = Group::create(['name' => $info['name'], 'owner_id' => $request->user()->id]);
    //         $group->members()->attach($request->user()->id, ['role' => 'owner']);

    //         return $group;
    //     });

    //     return redirect()->route('groups.show', $group);
    // }

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'emails' => 'nullable|array',
            'emails.*' => 'email|exists:users,email' // registered user
        ]);

        $group = DB::transaction(function () use ($validated, $request) {
            // create
            $group = Group::create([
                'name' => $validated['name'], 
                'owner_id' => $request->user()->id
            ]);

            // owner
            $group->members()->attach($request->user()->id, ['role' => 'owner']);

            // member
            if (!empty($validated['emails'])) {
                $userIds = \App\Models\User::whereIn('email', $validated['emails'])->pluck('id');
                
                // member emails array
                $memberData = [];
                foreach ($userIds as $userId) {
                    if ($userId !== $request->user()->id) {
                        $memberData[$userId] = ['role' => 'member'];
                    }
                }
                if (!empty($memberData)) {
                    $group->members()->attach($memberData);
                }
            }

            return $group;
        });

        return redirect()->route('groups.show', $group);
    }

    // show each group deets
    public function show(Group $group) {
        Gate::authorize('view', $group);

        $members = $group->members()->orderBy('name', 'asc')->get();
        $tasks = $group->tasks()->with(['tag', 'user'])
                        ->orderBy('is_done')
                        ->orderByRaw('due_at IS NULL')
                        ->orderBy('due_at')
                        ->get();
        $tags = Tag::mine()->get();
        $priorities = Task::priorities;

        return view('groups.show', [
            'group' => $group,
            'members' => $members,
            'tasks' => $tasks,
            'tags' => $tags,
            'priorities' => $priorities,
        ]);
    }

    // delete groups
    public function destroy(Group $group) {
        Gate::authorize('delete', $group);

        $group->delete();

        return redirect()->route('groups.show');
    }
}
