<?php

namespace App\Http\Controllers\API\User;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\UserUpdatingRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserManagmentController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;  
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_list = $this->userService->getAll();

        return Response::apiSuccess($user_list,Constant::USERS_RECEIVED_SUCCESS_MESSAGE,JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRegistrationRequest $request)
    {
        $user_data = $this->userService->generate($request->validated());

        return Response::apiSuccess($user_data,Constant::USER_CREATED_SUCCESS_MESSAGE,JsonResponse::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdatingRequest $request, string $id)
    {
        $user_data = $this->userService->update($id,$request->validated());

        return Response::apiSuccess($user_data,Constant::USER_UPDATED_SUCCESS_MESSAGE,JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        if($request->user('api')->id == $id)
        {
            return Response::apiError(Constant::USER_SELF_DELETING_ERROR_MESSAGE,[],JsonResponse::HTTP_BAD_REQUEST);
        }
        $this->userService->remove($id);
        return Response::apiSuccess([],Constant::USER_DELETED_SUCCESS_MESSAGE,JsonResponse::HTTP_OK);
    }
}
