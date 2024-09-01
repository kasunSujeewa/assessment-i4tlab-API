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
    protected $task;
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function findAll(User $user,$params = null)
    {
        return $this->task::getAllByOwner($user,$params);
    }

    public function show(int $id, User $user)
    {
        $show_task = $this->task::findOneByOWner($id, $user);

        if ($show_task == null) 
        {
            throw new CustomNotFoundException();
        } 
        else 
        {
            return $show_task;
        }
    }

    public function store(array $data, User $user)
    {
        return $this->task::store($data, $user);
    }

    public function update(int $id, array $data, User $user)
    {
        $update_task = $this->task::findOneByOWner($id, $user);

        if ($update_task == null) 
        {
            throw new CustomNotFoundException();
        } 
        else 
        {
            if ($this->task->modifyByOwner($update_task, $data)) 
            {
                return $this->task::findOneByOWner($id, $user);
            } 
            else 
            {
                throw new CustomServerErrorException();
            }
        }
    }

    public function delete(int $id, User $user)
    {
        $show_task = $this->task::findOneByOWner($id, $user);

        if ($show_task == null) 
        {
            throw new CustomNotFoundException();
        } 
        else 
        {
            if ($this->task->remove($show_task)) 
            {
                return true;
            } 
            else 
            {
                throw new CustomServerErrorException();
            }
        }
    }
}
