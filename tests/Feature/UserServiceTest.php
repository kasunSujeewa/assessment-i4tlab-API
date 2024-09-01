<?php

namespace Tests\Feature;

use App\Exceptions\CustomNotFoundException;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure users table is migrated
        Schema::hasTable('users');

        // Initialize the UserService with dependency injection
        $this->userService = new UserService(new User());
    }

    /** @test */
    public function it_can_create_a_user()
    {
        // Arrange: Prepare user data
        $data = [
            'name' => 'Test users',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
            'confirm_password' => 'password123',
        ];

        // Act: Generate user
        $user = $this->userService->generate($data);

        // Assert: Check if the user exists in the database
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

    /** @test */
    public function it_can_update_a_user()
    {
        // Arrange: Create a user and prepare updated data
        $user = User::factory()->create();
        $updateData = [
            'name' => 'Test User',
            'email' => 'testUser@example.com',
        ];

        // Act: Update user
        $updatedUser = $this->userService->update($user->id, $updateData);

        // Assert: Check if the user data is updated
        $this->assertEquals('Test User', $updatedUser->name);
        $this->assertEquals('testUser@example.com', $updatedUser->email);
    }

    /** @test */
    public function it_can_get_all_users()
    {
        // Arrange: Create some users
        User::factory()->count(3)->create();

        // Act: Retrieve all users
        $allUsers = $this->userService->getAll();

        // Assert: Check if all users are returned
        $this->assertCount(3, $allUsers);
    }

    /** @test */
    public function it_can_get_active_users_list()
    {
        // Arrange: Create users with active and inactive statuses
        User::factory()->count(2)->create(['is_available' => true,'role' => 'User']);
        User::factory()->count(3)->create(['is_available' => false,'role' => 'User']);

        // Act: Retrieve only active users
        $activeUsers = $this->userService->getList();

        // Assert: Check if only active users are returned
        $this->assertCount(2, $activeUsers);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        // Arrange: Create a user
        $user = User::factory()->create();

        // Act: Remove user
        $this->userService->remove($user->id);

        // Assert: Check if the user does not exist in the database
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_throws_not_found_exception_when_user_not_found_for_update()
    {
        $this->expectException(CustomNotFoundException::class);

        // Act: Try to update a non-existing user
        $this->userService->update(999, ['name' => 'Not Found']);
    }

}
