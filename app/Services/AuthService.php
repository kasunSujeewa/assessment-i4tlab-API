<?php

namespace App\Services;

use App\Exceptions\CustomAuthException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    
    public function register(array $data)
    {
        $auth_user = $this->user::store($data);

        return $auth_user->createToken('API Token')->plainTextToken;
    }

    public function login(array $data)
    {
       
        $auth_user = $this->user::userFindByEmail($data['email']);

        if (!$auth_user || !Hash::check($data['password'], $auth_user->password)) 
        {
            throw new CustomAuthException();
        }

        return $auth_user->createToken('API Token')->plainTextToken;
    }
}
