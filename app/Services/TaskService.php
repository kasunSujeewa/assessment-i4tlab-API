<?php

namespace App\Services;

use App\Exceptions\CustomAuthException;
use App\Exceptions\CustomNotFoundException;
use App\Exceptions\CustomServerErrorException;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TaskService
{

    public function findAll(Task $task)
    {
        return $task->getAll();
    }

    public function show(int $id, Task $task)
    {
        $show_task = $task->findOne($id);

        if ($show_task == null) {
            return new CustomNotFoundException();
        } else {
            return $show_task;
        }
    }

    public function store(array $data, Task $task)
    {
        return $task->store($data);
    }

    public function update(int $id, array $data, Task $task)
    {
        $update_task = $task->findOne($id);

        if ($update_task == null) {
            return new CustomNotFoundException();
        } else {
            if ($task->modify($update_task, $data)) {
                return $task->findOne($id);
            } else {
                return new CustomServerErrorException();
            }
        }
    }

    public function delete(int $id, Task $task)
    {
        $show_task = $task->findOne($id);

        if ($show_task == null) {
            return new CustomNotFoundException();
        } else {
            if ($task->remove($show_task)) {
                return true;
            } else {
                return new CustomServerErrorException();
            }
        }
    }
}
