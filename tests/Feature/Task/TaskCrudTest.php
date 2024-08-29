<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $regularUser;
    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user and a regular user
        $this->adminUser = User::factory()->create(['role' => 'Admin']);
        $this->regularUser = User::factory()->create(['role' => 'User']);
    }

    /** @test */
    public function admin_can_create_a_task()
    {
        // Authenticate as the admin user
        $this->actingAs($this->adminUser, 'sanctum');

        $response = $this->postJson('/api/tasks', [
            'title' => 'New Task',
            'status' => 'Pending',
            'user_id' => $this->adminUser->id,
            'due_date' => '2024-09-10',
        ]);

        $response->assertStatus(201); // Ensure the status code is 201
        $response->assertJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    /** @test */
    public function regular_user_cannot_create_a_task()
    {
        // Authenticate as a regular user
        $this->actingAs($this->regularUser, 'sanctum');

        $response = $this->postJson('/api/tasks', [
            'title' => 'New Task',
            'status' => 'Pending',
            'user_id' => $this->regularUser->id,
            'due_date' => '2024-09-10',
        ]);

        $response->assertStatus(403); // Forbidden
        $response->assertJson([
            'success' => false,
            'data' => [],
            'message' => 'Forbidden. You do not have the required permissions to access this resource.',
        ]);
    }

    /** @test */
    public function admin_can_read_a_task()
    {
        // Authenticate as the admin user
        $this->actingAs($this->adminUser, 'sanctum');

        $task = Task::factory()->create(['user_id' => $this->adminUser->id]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Task Received Successfully',
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    /** @test */
    public function regular_user_cannot_read_a_task()
    {
        // Authenticate as a regular user
        $this->actingAs($this->regularUser, 'sanctum');

        $task = Task::factory()->create(['user_id' => $this->adminUser->id]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(403); // Forbidden
        $response->assertJson([
            'success' => false,
            'message' => 'Forbidden. You do not have the required permissions to access this resource.',
        ]);
    }

    /** @test */
    public function admin_can_update_a_task()
    {
        // Authenticate as the admin user
        $this->actingAs($this->adminUser, 'sanctum');

        $task = Task::factory()->create(['user_id' => $this->adminUser->id]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'status' => 'In Progress',
        ]);

        $response->assertStatus(206);
        $response->assertJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    /** @test */
    public function regular_user_cannot_update_a_task()
    {
        // Authenticate as a regular user
        $this->actingAs($this->regularUser, 'sanctum');

        $task = Task::factory()->create(['user_id' => $this->adminUser->id]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'status' => 'In Progress',
        ]);

        $response->assertStatus(403); // Forbidden
        $response->assertJson([
            'success' => false,
            'message' => 'Forbidden. You do not have the required permissions to access this resource.',
        ]);
    }

    /** @test */
    public function admin_can_delete_a_task()
    {
        // Authenticate as the admin user
        $this->actingAs($this->adminUser, 'sanctum');

        $task = Task::factory()->create(['user_id' => $this->adminUser->id]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
    }

    /** @test */
    public function regular_user_cannot_delete_a_task()
    {
        // Authenticate as a regular user
        $this->actingAs($this->regularUser, 'sanctum');

        $task = Task::factory()->create(['user_id' => $this->adminUser->id]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(403); // Forbidden
        $response->assertJson([
            'success' => false,
            'message' => 'Forbidden. You do not have the required permissions to access this resource.',
        ]);
    }
}
