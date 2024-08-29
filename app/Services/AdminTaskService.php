<?php

namespace App\Services;

use App\Contracts\TaskService;
use App\Exceptions\CustomAuthException;
use App\Exceptions\CustomNotFoundException;
use App\Exceptions\CustomServerErrorException;
use App\Models\Task;
use App\Models\User;

class AdminTaskService implements TaskService
{

    public function findAll(Task $task, User $user)
    {
        return $task->getAllByOwner($task, $user);
    }

    public function show(int $id, Task $task, User $user)
    {
        $show_task = $task->findOneByOWner($id, $user, $task);

        if ($show_task == null) {
            throw new CustomNotFoundException();
        } else {
            return $show_task;
        }
    }

    public function store(array $data, Task $task, User $user)
    {
        return $task->store($data, $task, $user);
    }

    public function update(int $id, array $data, Task $task, User $user)
    {
        $update_task = $task->findOneByOWner($id, $user, $task);

        if ($update_task == null) {
            throw new CustomNotFoundException();
        } else {
            if ($task->modifyByOwner($update_task, $data)) {
                return $task->findOneByOWner($id, $user);
            } else {
                throw new CustomServerErrorException();
            }
        }
    }

    public function delete(int $id, Task $task, User $user)
    {
        $show_task = $task->findOneByOWner($id, $user, $task);

        if ($show_task == null) {
            throw new CustomNotFoundException();
        } else {
            if ($task->remove($show_task)) {
                return true;
            } else {
                throw new CustomServerErrorException();
            }
        }
    }
}
