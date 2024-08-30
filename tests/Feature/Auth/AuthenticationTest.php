<?php

namespace Tests\Feature\Auth;

use App\Constants\Constant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'confirm_password' => 'password',
        ]);

        $response->assertStatus(JsonResponse::HTTP_CREATED)
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function user_can_login()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(JsonResponse::HTTP_OK)
                 ->assertJson([
                     'success' => true,
                 ]);
    }

    /** @test */
    public function registration_requires_valid_email()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password',
            'confirm_password' => 'password',
        ]);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
                 ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function login_requires_valid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED)
                 ->assertJson([
                     'success' => false,
                     'message' => Constant::INVALID_LOGINS,
                 ]);
    }
}
