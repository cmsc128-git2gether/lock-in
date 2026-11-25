<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // there are at least 5 users
        $users = User::all();
        if ($users->count() < 5) {
            $users = $users->concat(User::factory(5 - $users->count())->create());
        }

        $main   = $users->firstWhere('email', 'test@example.com') ?? $users->first();
        $others = $users->where('id', '!=', $main->id)->values();

        // [owner, group name, is the main user added as a member?]
        $plans = [
            [$main,      'Capstone Project', true],
            [$main,      'Household Chores', true],
            [$others[0], 'Study Group',      true],   // shows under "Shared with me"
            [$others[1], 'Weekend Trip',     true],   // shows under "Shared with me"
            [$others[2], 'Secret Club',      false],  // the main user must NOT see this one
        ];

        foreach ($plans as [$owner, $name, $mainJoins]) {
            // group
            $group = Group::factory()->create(['owner_id' => $owner->id, 'name' => $name]);

            // members
            $pool = $users->where('id', '!=', $owner->id);
            if (! $mainJoins) {
                $pool = $pool->where('id', '!=', $main->id);
            }

            $members = $pool->shuffle()->take(2);
            if ($mainJoins && $owner->id !== $main->id && ! $members->contains('id', $main->id)) {
                $members->push($main);
            }

            $group->members()->attach($owner->id, ['role' => 'owner']);
            $group->members()->attach(
                $members->mapWithKeys(fn ($user) => [$user->id => ['role' => 'member']])->all()
            );

            // tasks
            $everyone = collect([$owner])->concat($members);

            foreach ($everyone as $person) {
                $open = Task::factory()->count(2)->for($person)->create(['is_done' => false]);
                $done = Task::factory()->count(1)->for($person)->create(['is_done' => true]);

                $group->tasks()->attach($open->concat($done)->pluck('id')->all());
            }

            // one deleted task per group
            $trashed = Task::factory()->for($owner)->trashed()->create();
            $group->tasks()->attach($trashed->id);
        }
    }
}
