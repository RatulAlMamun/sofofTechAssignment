<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    public function test_user_can_assign_task_to_single_user()
    {
        $owner = $this->authenticate();
        $task = Task::factory()->create(['user_id' => $owner->id]);
        $user = User::factory()->create();
        $response = $this->postJson("/api/tasks/{$task->id}/assign", [
            'user_id' => $user->id
        ]);
        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseHas('task_user', [
            'task_id' => $task->id,
            'user_id' => $user->id
        ]);
    }

    public function test_duplicate_user_assignment_is_ignored()
    {
        $creator = $this->authenticate();
        $task = Task::factory()->create(['user_id' => $creator->id]);
        $user = User::factory()->create();
        $task->assignedUsers()->attach($user->id);
        $response = $this->postJson("/api/tasks/{$task->id}/assign", [
            'user_id' => $user->id
        ]);
        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertCount(1, $task->fresh()->assignedUsers);
    }
}
