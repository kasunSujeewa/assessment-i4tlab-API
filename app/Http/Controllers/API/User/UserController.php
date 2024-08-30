<?php

namespace App\Http\Controllers\API\User;

use App\Constants\Constant;
use App\Http\Controllers\API\BaseAPIController;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdatingRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseAPIController
{
    protected $user;
    protected $userService;
    protected $authUser;
    public function __construct(UserService $userService, User $user) {
        $this->user = $user;
        $this->userService = $userService;  
    }

    public function update(UserUpdatingRequest $request)
    {
        $user_data = $request->user('api');
        
        $user_data = $this->userService->update($user_data->id,$request->validated(),$this->user);

        return $this->successResponse($user_data,Constant::USER_UPDATED_SUCCESS_MESSAGE,JsonResponse::HTTP_PARTIAL_CONTENT);
    }
    public function getList()
    {
        
        $user_list = $this->userService->getList($this->user);

        return $this->successResponse($user_list,Constant::USERS_RECEIVED_SUCCESS_MESSAGE,JsonResponse::HTTP_OK);
    }
}
