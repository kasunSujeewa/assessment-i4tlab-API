<?php
namespace App\Contracts;

use App\Models\Task;
use App\Models\User;

interface TaskService
{
    public function findAll(Task $task, User $user);
    

    public function show(int $id, Task $task, User $user);
    

    public function store(array $data, Task $task, User $user);
    

    public function update(int $id, array $data, Task $task, User $user);
   

    public function delete(int $id, Task $task, User $user);
    
}