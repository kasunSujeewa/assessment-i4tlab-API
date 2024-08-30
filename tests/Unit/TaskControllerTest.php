<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Contracts\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;
    protected $taskServiceMock;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create an admin user for authenticated requests
        $this->adminUser = User::factory()->create(['role' => 'Admin']);

        // Mock TaskService
        $this->taskServiceMock = $this->createMock(TaskService::class);
        $this->app->instance(TaskService::class, $this->taskServiceMock);
    }

    /** @test */
    public function it_can_fetch_all_tasks()
    {
        $this->actingAs($this->adminUser, 'sanctum');

        $tasks = Task::factory()->count(5)->create();

        $this->taskServiceMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($tasks);

        $response = $this->getJson(route('tasks.index'));

        $response->assertStatus(JsonResponse::HTTP_OK)
                 ->assertJsonStructure([
                     'success',
                     'data' => [['id', 'title', 'status', 'user_id']],
                     'message'
                 ]);
    }

    /** @test */
    public function it_can_create_a_task()
    {
        $this->actingAs($this->adminUser, 'sanctum');

        $data = [
            'title' => $this->faker->sentence,
            'status' => 'Pending',
            'user_id' => $this->adminUser->id,
        ];

        $task = Task::factory()->make($data);

        $this->taskServiceMock
            ->expects($this->once())
            ->method('store')
            ->willReturn($task);

        $response = $this->postJson(route('tasks.store'), $data);

        $response->assertStatus(JsonResponse::HTTP_CREATED)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['id', 'title', 'status', 'user_id'],
                     'message'
                 ]);
    }

    /** @test */
    public function it_can_show_a_task()
    {
        $this->actingAs($this->adminUser, 'sanctum');

        $task = Task::factory()->create();

        $this->taskServiceMock
            ->expects($this->once())
            ->method('show')
            ->with($task->id)
            ->willReturn($task);

        $response = $this->getJson(route('tasks.show', $task->id));

        $response->assertStatus(JsonResponse::HTTP_OK)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['id', 'title', 'status', 'user_id'],
                     'message'
                 ]);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $this->actingAs($this->adminUser, 'sanctum');

        $task = Task::factory()->create();

        $data = [
            'title' => 'Updated Task Title',
            'status' => 'In Progress',
        ];

        $this->taskServiceMock
            ->expects($this->once())
            ->method('update')
            ->with($task->id, $data)
            ->willReturn($task->fill($data));

        $response = $this->putJson(route('tasks.update', $task->id), $data);

        $response->assertStatus(JsonResponse::HTTP_OK)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['id', 'title', 'status', 'user_id'],
                     'message'
                 ]);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $this->actingAs($this->adminUser, 'sanctum');

        $task = Task::factory()->create();

        $this->taskServiceMock
            ->expects($this->once())
            ->method('delete')
            ->with($task->id)
            ->willReturn(true);

        $response = $this->deleteJson(route('tasks.destroy', $task->id));

        $response->assertStatus(JsonResponse::HTTP_OK)
                 ->assertJson([
                     'success' => true,
                     'data' => [],
                     'message' => 'Task Deleted Successfully'
                 ]);
    }
}
