<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseAPIController;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends BaseAPIController
{
    protected $authService;
    protected $user;

    public function __construct(AuthService $authService, User $user)
    {
        $this->authService = $authService;
        $this->user = $user;
    }

    public function register(UserRegistrationRequest $request)
    {

        $token = $this->authService->register($request->validated(),$this->user);

        return $this->successResponse($token,"User Registered Successfully",JsonResponse::HTTP_CREATED);
    }

    public function login(UserLoginRequest $request)
    {

        $token = $this->authService->login($request->validated(),$this->user);

        return $this->successResponse($token,'User Login Successfull');
    } 
}
