<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Traits\CustomValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UserRegistrationRequest extends FormRequest
{
    use CustomValidationResponseTrait;
   
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
            'role' => new Enum(UserRole::class)
        ];
    }
}
