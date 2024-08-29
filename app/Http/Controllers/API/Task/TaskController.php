<?php

namespace App\Http\Controllers\API\Task;

use App\Constants\Constant;
use App\Contracts\TaskService;
use App\Http\Controllers\API\BaseAPIController;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoringRequest;
use App\Http\Requests\TaskUpdatingRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends BaseAPIController
{
    protected $taskService;
    protected $task;
    protected $request_user;
    public function __construct(TaskService $taskService, Task $task, Request $request) {
        $this->taskService = $taskService;
        $this->task = $task;
        $this->request_user = $request->user('api');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $tasks = $this->taskService->findAll($this->task,$this->request_user);

        return $this->successResponse($tasks,Constant::TASKS_RECEIVED_SUCCESS_MESSAGE,JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoringRequest $request)
    {
        $task = $this->taskService->store($request->validated(),$this->task,$this->request_user);

        return $this->successResponse($task,Constant::TASK_CREATED_SUCCESS_MESSAGE,JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = $this->taskService->show($id,$this->task,$this->request_user);

        return $this->successResponse($task,Constant::TASK_RECEIVED_SUCCESS_MESSAGE,JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdatingRequest $request, string $id)
    {
        $task = $this->taskService->update($id,$request->validated(),$this->task,$this->request_user);

        return $this->successResponse($task,Constant::TASK_UPDATED_SUCCESS_MESSAGE,JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->taskService->delete($id,$this->task,$this->request_user);

        return $this->successResponse([],Constant::TASK_DELETED_SUCCESS_MESSAGE,JsonResponse::HTTP_NO_CONTENT);
    }
}
