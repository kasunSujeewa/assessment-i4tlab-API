<?php

namespace App\Services;

use App\Contracts\TaskService;
use App\Exceptions\CustomNotFoundException;
use App\Exceptions\CustomServerErrorException;
use App\Models\Task;
use App\Models\User;

class UserTaskService implements TaskService
{
    protected $task;
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function findAll(User $user, $params = null)
    {
        $query = $this->task::with(['owner', 'worker'])->where('worker_id', $user->id)->orderBy('created_at', 'desc');

        if (in_array($params, ['Pending', 'In Progress', 'Completed'])) {
            $query->where('status', $params);
        }

        return $query->paginate(10);
    }

    public function show(int $id, User $user)
    {
        $show_task = $this->task::with(['owner', 'worker'])->where('id', $id)->where('worker_id', $user->id)->first();

        if ($show_task == null) {
            throw new CustomNotFoundException();
        } else {
            return $show_task;
        }
    }

    public function store(array $data, User $user) {}

    public function update(int $id, array $data, User $user)
    {
        try {

            $update_task = $this->show($id, $user);

            if ($update_task === null) {
                throw new CustomNotFoundException();
            }

            if ($update_task->update($data)) {
                return $this->show($id, $user);
            } else {
                throw new CustomServerErrorException();
            }
        } catch (CustomNotFoundException $e) {
            throw $e;
        } catch (CustomServerErrorException $e) {

            throw $e;
        } catch (\Exception $e) {
            throw new \Exception('An unexpected error occurred. Please try again later.', 0, $e);
        }
    }


    public function delete(int $id, User $user) {}
}
