<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    public function test_authenticated_user_can_create_task()
    {
        $this->authenticate();
        $response = $this->postJson('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'Testing task creation',
            'due_date' => now()->addDays(3)->format('Y-m-d'),
            'status' => 'todo',
            'priority' => 'high'
        ]);
        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Test Task')
            ->assertJsonPath('success', true);
    }

    public function test_user_can_view_their_tasks()
    {
        $user = $this->authenticate();
        Task::factory()->create(['user_id' => $user->id]);
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(200)->assertJsonStructure(['data']);
    }

    public function test_task_can_be_updated()
    {
        $user = $this->authenticate();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'description' => 'Updated',
            'due_date' => now()->addDays(2)->format('Y-m-d'),
            'status' => 'in_progress',
            'priority' => 'medium'
        ]);
        $response->assertStatus(200)->assertJsonFragment(['title' => 'Updated Task']);
    }

    public function test_task_can_be_deleted()
    {
        $user = $this->authenticate();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $response = $this->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
