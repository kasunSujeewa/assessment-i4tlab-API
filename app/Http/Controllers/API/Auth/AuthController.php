<?php

namespace App\Http\Controllers\API\Auth;

use App\Constants\Constant;
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

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(UserRegistrationRequest $request) : JsonResponse
    {

        $token = $this->authService->register($request->validated());

        return $this->successResponse($token,Constant::REGISTERED_SUCCESS_MESSAGE,JsonResponse::HTTP_CREATED);
    }

    public function login(UserLoginRequest $request) : JsonResponse
    {

        $token = $this->authService->login($request->validated());

        return $this->successResponse($token,Constant::LOGGED_SUCCESS_MESSAGE);
    } 

    public function logout(Request $request)
    {
        $request->user('api')->currentAccessToken()->delete();

        return $this->successResponse([],Constant::LOGOUT_SUCCESS_MESSAGE);
    }
}
