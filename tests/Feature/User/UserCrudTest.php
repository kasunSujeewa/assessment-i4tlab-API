<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $actingUser;
    protected function setUp(): void
    {
        parent::setUp();
    
        Schema::hasTable('users');
        
        // Create a user and assign a Sanctum token
        $this->actingUser = User::factory()->create(['role'=>'Admin']);
        Sanctum::actingAs($this->actingUser, ['*']); 
    }

    /** @test */
    public function user_can_be_created()
    {
        // Arrange: Prepare user data
        $data = [
            'name' => 'Test Data',
            'email' => 'test@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
        ];

        $response = $this->postJson('/api/system/user', $data);

        // Assert: Check response and database
        $response->assertStatus(JsonResponse::HTTP_CREATED)
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function user_can_be_updated()
    {
        // Arrange: Create a user and prepare updated data
        $user = User::factory()->create();
        $updateData = [
            'name' => 'user Test',
            'email' => 'userTest@example.com',
        ];

        // Act: Send a PUT request to the update route
        $response = $this->putJson("/api/system/user/{$user->id}", $updateData);

        // Assert: Check response and updated user data
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'user Test',
            'email' => 'userTest@example.com',
        ]);
    }

    /** @test */
    public function user_list_can_be_retrieved()
    {
        // Arrange: Create some users
        User::factory()->count(3)->create();

        // Act: Send a GET request to the users index route
        $response = $this->getJson('/api/system/user');

        // Assert: Check response and count of users
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);

        $response->assertJsonCount(4, 'data');
    }

    /** @test */
    public function user_can_be_deleted()
    {
        // Arrange: Create a user
        $user = User::factory()->create();

        // Act: Send a DELETE request to the delete route
        $response = $this->deleteJson("/api/system/user/".$user->id);

        // Assert: Check response and database
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function update_user_requires_valid_id()
    {
        // Act: Send a PUT request with invalid user ID
        $response = $this->putJson('/api/system/user/999', ['name' => 'Nonexistent User']);

        // Assert: Check for not found response
        $response->assertStatus(404);
    }

    /** @test */
    public function delete_user_requires_valid_id()
    {
        // Act: Send a DELETE request with invalid user ID
        $response = $this->deleteJson('/api/system/user/999');

        // Assert: Check for not found response
        $response->assertStatus(404);
    }
}
