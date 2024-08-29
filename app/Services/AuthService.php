<?php

namespace App\Services;

use App\Exceptions\CustomAuthException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthService
{
    

    public function register(array $data, User $user)
    {
        $auth_user = $user->store($data);

        return $auth_user->createToken('API Token')->plainTextToken;
    }

    public function login(array $data,User $user)
    {
       
        $user = $user->userFindByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new CustomAuthException();
        }

        return $user->createToken('API Token')->plainTextToken;
    }
}
