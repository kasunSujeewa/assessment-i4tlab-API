<?php

namespace App\Services;

use App\Exceptions\CustomNotFoundException;
use App\Exceptions\CustomServerErrorException;
use App\Models\User;

class UserService
{

    public function update(int $id, array $data, User $user)
    {
        $update_user = $user->findOne($id,$user);

        if ($update_user == null) {
            throw new CustomNotFoundException();
        } else {
            if ($user->modify($update_user, $data)) {
                return $user->findOne($id);
            } else {
                throw new CustomServerErrorException();
            }
        }
    }
    public function getList(User $user)
    {
        $all_active_users = $user->findAllActive($user);

        if (count($all_active_users) == 0) {
            throw new CustomNotFoundException();
        } else {
            return $all_active_users;
        }
    }
}
