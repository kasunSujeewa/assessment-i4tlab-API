<?php

namespace App\Http\Controllers\API\Task;

use App\Http\Controllers\API\BaseAPIController;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoringRequest;
use App\Http\Requests\TaskUpdatingRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends BaseAPIController
{
    protected $taskService;
    protected $task;
    public function __construct(TaskService $taskService, Task $task) {
        $this->taskService = $taskService;
        $this->task = $task;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = $this->taskService->findAll($this->task);

        return $this->successResponse($tasks,"Tasks Received Successfully",JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoringRequest $request)
    {
        $task = $this->taskService->store($request->validated(),$this->task);

        return $this->successResponse($task,"Tasks Created Successfully",JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = $this->taskService->show($id,$this->task);

        return $this->successResponse($task,"Task Received Successfully",JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdatingRequest $request, string $id)
    {
        $task = $this->taskService->update($id,$request->validated(),$this->task);

        return $this->successResponse($task,"Task Updated Successfully",JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = $this->taskService->delete($id,$this->task);

        return $this->successResponse([],"Task Deleted Successfully",JsonResponse::HTTP_NO_CONTENT);
    }
}
