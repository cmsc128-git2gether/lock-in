<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private function validData(array $overrides = []): array
    {
        $tag = Tag::forceCreate(['name' => 'Work', 'color' => '#3b82f6']);

        return array_merge([
            'title'    => 'Write report',
            'priority' => 'High',
            'due_at'   => now()->addDay()->format('Y-m-d H:i:s'),
            'tag_id'   => $tag->id,
        ], $overrides);
    }

    // ---------- create ----------

    public function test_guest_cannot_create_a_task(): void
    {
        $this->post('/tasks', $this->validData())->assertRedirect('/login');
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_user_can_create_a_task(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/tasks', $this->validData())
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('tasks', [
            'title'   => 'Write report',
            'user_id' => $user->id,
        ]);
    }

    public function test_title_is_required(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/tasks', $this->validData(['title' => '']))
            ->assertSessionHasErrors('title');

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_priority_must_be_valid(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/tasks', $this->validData(['priority' => 'Urgent']))
            ->assertSessionHasErrors('priority');
    }

    // ---------- read ----------

    public function test_dashboard_shows_only_my_tasks(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();

        Task::factory()->for($me)->create(['title' => 'Mine']);
        Task::factory()->for($other)->create(['title' => 'Not mine']);

        $this->actingAs($me)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('tasks', fn ($tasks) =>
                $tasks->count() === 1 && $tasks->first()->title === 'Mine'
            );
    }

    // ---------- update ----------

    public function test_user_can_mark_a_task_done(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create(['is_done' => false]);

        $this->actingAs($user)
            ->patch("/tasks/{$task->id}", ['is_done' => 1])
            ->assertRedirect(route('dashboard'));

        $this->assertTrue($task->fresh()->is_done);
    }

    public function test_user_can_edit_a_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $this->actingAs($user)
            ->patch("/tasks/{$task->id}/submit", $this->validData(['title' => 'Updated title']))
            ->assertRedirect(route('dashboard'));

        $this->assertSame('Updated title', $task->fresh()->title);
    }

    // ---------- delete / undo / force delete ----------

    public function test_delete_soft_deletes_the_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $this->actingAs($user)
            ->deleteJson("/tasks/{$task->id}/destroy")
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertSoftDeleted($task);
    }

    public function test_user_can_restore_a_deleted_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->trashed()->create();

        $this->actingAs($user)
            ->patchJson("/tasks/{$task->id}/restore")
            ->assertOk();

        $this->assertNotSoftDeleted($task);
    }

    public function test_user_can_permanently_delete_a_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->trashed()->create();

        $this->actingAs($user)
            ->deleteJson("/tasks/{$task->id}/force")
            ->assertOk();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_trash_page_lists_only_my_deleted_tasks(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();

        Task::factory()->for($me)->trashed()->create(['title' => 'My trashed']);
        Task::factory()->for($me)->create(['title' => 'My active']);
        Task::factory()->for($other)->trashed()->create(['title' => 'Their trashed']);

        $this->actingAs($me)
            ->get('/tasks/trash')
            ->assertOk()
            ->assertViewHas('tasks', fn ($tasks) =>
                $tasks->count() === 1 && $tasks->first()->title === 'My trashed'
            );
    }

    // ---------- security ----------

    public function test_cannot_delete_another_users_task(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        $task = Task::factory()->for($other)->create();

        $this->actingAs($me)
            ->deleteJson("/tasks/{$task->id}/destroy")
            ->assertNotFound();

        $this->assertNotSoftDeleted($task);
    }

    public function test_cannot_edit_another_users_task(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        $task = Task::factory()->for($other)->create(['title' => 'Original']);

        $this->actingAs($me)
            ->patch("/tasks/{$task->id}/submit", $this->validData(['title' => 'Hacked']))
            ->assertNotFound();

        $this->assertSame('Original', $task->fresh()->title);
    }
}