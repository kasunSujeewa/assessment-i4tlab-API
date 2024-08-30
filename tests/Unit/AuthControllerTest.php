<?php

namespace Tests\Unit;

use App\Constants\Constant;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    protected $authServiceMock;
    protected $userModelMock;
    protected $authController;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the AuthService
        $this->authServiceMock = Mockery::mock(AuthService::class);
        $this->userModelMock = Mockery::mock(User::class);

        // Instantiate the controller with mocks
        $this->authController = new AuthController($this->authServiceMock, $this->userModelMock);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_register_a_user()
    {
        $requestData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'confirm_password' => 'password',
        ];

        $mockedToken = 'mocked-token';
        $this->authServiceMock->shouldReceive('register')
            ->once()
            ->with($requestData, $this->userModelMock)
            ->andReturn($mockedToken);

        $request = Mockery::mock(UserRegistrationRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($requestData);

        $response = $this->authController->register($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->status());
        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'success' => true,
                'message' => Constant::REGISTERED_SUCCESS_MESSAGE,
                'data' => $mockedToken
            ]),
            $response->getContent()
        );
    }

    /** @test */
    public function it_can_login_a_user()
    {
        $requestData = [
            'email' => 'john@example.com',
            'password' => 'password',
        ];

        $mockedToken = 'mocked-token';
        $this->authServiceMock->shouldReceive('login')
            ->once()
            ->with($requestData, $this->userModelMock)
            ->andReturn($mockedToken);

        $request = Mockery::mock(UserLoginRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($requestData);

        $response = $this->authController->login($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'success' => true,
                'message' => Constant::LOGGED_SUCCESS_MESSAGE,
                'data' => $mockedToken
            ]),
            $response->getContent()
        );
    }
}
