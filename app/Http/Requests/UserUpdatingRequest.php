<?php

namespace App\Http\Requests;

use App\Traits\CustomValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdatingRequest extends FormRequest
{
    use CustomValidationResponseTrait;
   
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'email' => [
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user('api')->id), // Exclude the current user's ID
            ],
            'password' => 'string|min:8',
            'confirm_password' => 'required_with:password|string|min:8|same:password',
            'is_available' => 'boolean',
        ];
    }
}
