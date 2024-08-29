<?php

namespace App\Services;

use App\Contracts\TaskService;
use App\Exceptions\CustomNotFoundException;
use App\Exceptions\CustomServerErrorException;
use App\Models\Task;
use App\Models\User;

class UserTaskService implements TaskService
{

    public function findAll(Task $task, User $user)
    {
        return $task->getAllByWoker($task,$user);
    }

    public function show(int $id, Task $task, User $user)
    {
        $show_task = $task->findOnebyWorker($id,$user,$task);

        if ($show_task == null) {
            throw new CustomNotFoundException();
        } else {
            return $show_task;
        }
    }

    public function store(array $data, Task $task, User $user)
    {
        
    }

    public function update(int $id, array $data, Task $task, User $user)
    {
        $update_task = $task->findOnebyWorker($id,$user,$task);

        if ($update_task == null) {
            throw new CustomNotFoundException();
        } else {
            if ($task->modifyByWorker($update_task, $data)) {
                return $task->findOnebyWorker($id,$user);
            } else {
                throw new CustomServerErrorException();
            }
        }
    }

    public function delete(int $id, Task $task, User $user)
    {
       
    }
}
