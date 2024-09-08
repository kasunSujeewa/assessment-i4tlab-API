<?php

namespace App\Http\Controllers\API\User;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdatingRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;  
    }

    public function update(UserUpdatingRequest $request)
    {
        $user_data = $request->user('api');
        
        $user_data = $this->userService->update($user_data->id,$request->validated(),);

        return Response::apiSuccess($user_data,Constant::USER_UPDATED_SUCCESS_MESSAGE,JsonResponse::HTTP_PARTIAL_CONTENT);
    }
    public function getList()
    {
        
        $user_list = $this->userService->getList();

        return Response::apiSuccess($user_list,Constant::USERS_RECEIVED_SUCCESS_MESSAGE,JsonResponse::HTTP_OK);
    }
    public function ownData(Request $request)
    {

        return Response::apiSuccess($request->user('api'),Constant::USER_RECEIVED_SUCCESS_MESSAGE,JsonResponse::HTTP_OK);
    }
}
