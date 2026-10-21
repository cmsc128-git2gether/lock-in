<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GroupMemberController extends Controller
{
    // adding a group member
    public function store(Request $request, Group $group) {
        Gate::authorize('manageMembers', $group);

        $data = $request->validate(
            ['email' => 'required|email|exists:users,email'],
            ['email.exists' => 'No user is registered with that email address.']
        );

        $user = User::where('email', $data['email'])->first();

        if ($group->members()->whereKey($user->id)->exists()) {
            return back()->withErrors(['email' => 'User is already added on this group.']);
        }

        $group->members()->attach($user->id, ['role' => 'member']);

        return back()->with('status', "{$user->name} was added.");
    }

    // remove member or leave group
    public function destroy(Group $group, User $user) {
        $leave = (int) auth()->id() === (int) $user->id;

        Gate::authorize($leave ? 'view' : 'manageMembers', $group);

        if ((int) $user->id === (int) $group->owner_id) {
            return back()->withErrors(['member' => 'Owner cannot be removed in this group.']);
        }

        $group->members()->detach($user->id);

        return $leave ? redirect()->route('groups.index') : back();
    } 
}
