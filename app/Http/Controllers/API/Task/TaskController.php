<?php

namespace App\Http\Controllers\API\Task;

use App\Constants\Constant;
use App\Contracts\TaskService;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoringRequest;
use App\Http\Requests\TaskUpdatingRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TaskController extends Controller
{
    protected $taskService;
    protected $request_user;
    public function __construct(TaskService $taskService, Request $request) {
        $this->taskService = $taskService;
        $this->request_user = $request->user('api');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {       
        
        $tasks = $this->taskService->findAll($this->request_user,$request->get('status'));

        return Response::apiSuccess($tasks,Constant::TASKS_RECEIVED_SUCCESS_MESSAGE,JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoringRequest $request)
    {
        $task = $this->taskService->store($request->validated(),$this->request_user);

        return Response::apiSuccess($task,Constant::TASK_CREATED_SUCCESS_MESSAGE,JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = $this->taskService->show($id,$this->request_user);

        return Response::apiSuccess($task,Constant::TASK_RECEIVED_SUCCESS_MESSAGE,JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdatingRequest $request, string $id)
    {
        $task = $this->taskService->update($id,$request->validated(),$this->request_user);

        return Response::apiSuccess($task,Constant::TASK_UPDATED_SUCCESS_MESSAGE,JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->taskService->delete($id,$this->request_user);

        return Response::apiSuccess([],Constant::TASK_DELETED_SUCCESS_MESSAGE,JsonResponse::HTTP_OK);
    }
}
