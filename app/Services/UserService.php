<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Exceptions\CustomNotFoundException;
use App\Exceptions\CustomServerErrorException;
use App\Models\User;

class UserService
{
    protected $user;
    public function __construct(User $user) 
    {
        $this->user = $user;
    }

    public function update(int $id, array $data)
    {
        $update_user = $this->user::find($id);

        if ($update_user == null) 
        {
            throw new CustomNotFoundException();
        } 
        else 
        { 
            $user_data = $update_user->update($data);
            return $user_data;  
        }
    }

    public function getList()
    {
        return $this->user::where('role', UserRole::User)->where('is_available', true)->withCount(['workingTasks as working_tasks_count' => function ($query) {
            $query->where('status', TaskStatus::Pending)
                ->orWhere('status', TaskStatus::Progress);
        }])->orderBy('working_tasks_count', 'asc')->get();
        
        
    }

    public function getAll()
    {
        $all_users = $this->user->orderBy('created_at','desc')->get();
        
        return $all_users;
        
    }
    
    public function generate($data)
    {
        $user_data = $this->user::create($data);
        return $user_data;
    }

    public function remove($id)
    {
        $user_data = $this->user::find($id);

        if($user_data == null)
        {
            throw new CustomNotFoundException();
        }
        $user_data->delete();
    }
}
