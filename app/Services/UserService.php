<?php

namespace App\Services;

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
        $update_user = $this->user::findOne($id);

        if ($update_user == null) 
        {
            throw new CustomNotFoundException();
        } 
        else 
        {
            if ($this->user::modify($update_user, $data)) 
            {
                return $this->user->findOne($id);
            }
            else 
            {
                throw new CustomServerErrorException();
            }
        }
    }

    public function getList()
    {
        $all_active_users = $this->user->findAllActive($this->user);

        if (count($all_active_users) == 0) 
        {
            throw new CustomNotFoundException();
        } 
        else 
        {
            return $all_active_users;
        }
    }

    public function getAll()
    {
        $all_users = $this->user->orderBy('created_at','desc')->get();

        if (count($all_users) == 0) 
        {
            throw new CustomNotFoundException();
        } 
        else 
        {
            return $all_users;
        }
    }
    
    public function generate($data)
    {
        $user_data = $this->user::store($data);
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
