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

    public function findAll(User $user, $params = null)
    {
        $query = $this->task::with(['owner', 'worker'])->where('user_id', $user->id)->orderBy('created_at', 'desc');

        if (in_array($params, ['Pending', 'In Progress', 'Completed'])) {
            $query->where('status', $params);
        }

        return $query->paginate(10);
    }

    public function show(int $id, User $user)
    {
        $show_task = $this->task::with(['owner', 'worker'])->where('id', $id)->where('user_id', $user->id)->first();

        if ($show_task == null) {
            throw new CustomNotFoundException();
        } else {
            return $show_task;
        }
    }

    public function store(array $data, User $user)
    {
        $data['user_id'] = $user->id;
        return $this->task::create($data);
    }

    public function update(int $id, array $data, User $user)
    {
        $update_task = $this->show($id, $user);

        if ($update_task == null) {
            throw new CustomNotFoundException();
        } else {
            return $update_task->update($data);
        }
    }

    public function delete(int $id, User $user)
    {
        $show_task = $this->show($id, $user);

        if ($show_task == null) {
            throw new CustomNotFoundException();
        } else {
            return $this->task->remove($show_task);
        }
    }
}
