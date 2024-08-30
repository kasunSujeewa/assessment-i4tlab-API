<?php

namespace Tests\Feature\Task;

use App\Constants\Constant;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Tests\TestCase;

class TaskCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $regularUser;
    protected $request_user;
    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user and a regular user
        $this->adminUser = User::factory()->create(['role' => 'Admin']);
        $this->request_user = $this->adminUser;
        $this->regularUser = User::factory()->create(['role' => 'User']);

        FacadesRequest::setUserResolver(function() {
            return $this->request_user;
        });
    }

    /** @test */
    public function admin_can_create_a_task()
    {
        // Authenticate as the admin user
        $this->actingAs($this->adminUser, 'sanctum');
        
        $response = $this->postJson('/api/tasks', [
            'title' => 'New Task',
            'status' => 'Pending',
            'due_date' => '2024-09-10',
        ]);
        Log::info('error',['error' =>$response]);

        $response->assertStatus(JsonResponse::HTTP_CREATED); // Ensure the status code is 201
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

        $response->assertStatus(JsonResponse::HTTP_FORBIDDEN); // Forbidden
        $response->assertJson([
            'success' => false,
            'data' => [],
            'message' => Constant::FORBIDDEN,
        ]);
    }

    /** @test */
    public function admin_can_read_a_task()
    {
        // Authenticate as the admin user
        $this->actingAs($this->adminUser, 'sanctum');

        $task = Task::factory()->create(['user_id' => $this->adminUser->id]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJson([
            'success' => true,
            'message' => Constant::TASKS_RECEIVED_SUCCESS_MESSAGE,
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    /** @test */
    public function regular_user_can_read_a_task()
    {
        // Authenticate as a regular user
        $this->actingAs($this->regularUser, 'sanctum');

        $task = Task::factory()->create(['user_id' => $this->adminUser->id]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(JsonResponse::HTTP_OK); // Forbidden
        $response->assertJson([
            'success' => false,
            'message' => Constant::TASKS_RECEIVED_SUCCESS_MESSAGE,
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

        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJson([
            'success' => false,
            'message' => Constant::TASK_UPDATED_SUCCESS_MESSAGE,
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    /** @test */
    public function regular_user_can_update_a_task()
    {
        // Authenticate as a regular user
        $this->actingAs($this->regularUser, 'sanctum');

        $task = Task::factory()->create(['user_id' => $this->adminUser->id]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'status' => 'In Progress',
        ]);

        $response->assertStatus(JsonResponse::HTTP_OK); // Forbidden
        $response->assertJson([
            'success' => false,
            'message' => Constant::TASK_UPDATED_SUCCESS_MESSAGE,
        ]);
    }

    /** @test */
    public function admin_can_delete_a_task()
    {
        // Authenticate as the admin user
        $this->actingAs($this->adminUser, 'sanctum');

        $task = Task::factory()->create(['user_id' => $this->adminUser->id]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /** @test */
    public function regular_user_cannot_delete_a_task()
    {
        // Authenticate as a regular user
        $this->actingAs($this->regularUser, 'sanctum');

        $task = Task::factory()->create(['user_id' => $this->adminUser->id]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(JsonResponse::HTTP_FORBIDDEN); // Forbidden
        $response->assertJson([
            'success' => false,
            'message' => 'Forbidden. You do not have the required permissions to access this resource.',
        ]);
    }
}
