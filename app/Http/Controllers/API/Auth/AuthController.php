<?php

namespace App\Http\Controllers\API\Auth;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(UserRegistrationRequest $request) : JsonResponse
    {

        $token = $this->authService->register($request->validated());

        return Response::apiSuccess($token,Constant::REGISTERED_SUCCESS_MESSAGE,JsonResponse::HTTP_CREATED);
    }

    public function login(UserLoginRequest $request) : JsonResponse
    {

        $token = $this->authService->login($request->validated());

        return  Response::apiSuccess($token,Constant::LOGGED_SUCCESS_MESSAGE);
    } 

    public function logout(Request $request)
    {
        $request->user('api')->currentAccessToken()->delete();

        return  Response::apiSuccess([],Constant::LOGOUT_SUCCESS_MESSAGE);
    }
}
