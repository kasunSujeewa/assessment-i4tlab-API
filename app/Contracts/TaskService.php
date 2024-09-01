<?php
namespace App\Contracts;

use App\Models\User;

interface TaskService
{
    public function findAll(User $user, $params = null);
    

    public function show(int $id,User $user);
    

    public function store(array $data,User $user);
    

    public function update(int $id, array $data,User $user);
   

    public function delete(int $id,User $user);
    
}