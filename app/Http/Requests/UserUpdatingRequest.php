<?php

namespace App\Http\Requests;

use App\Traits\CustomValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdatingRequest extends FormRequest
{
    use CustomValidationResponseTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'password' => 'string|min:8',
            'is_available' => 'boolean',
        ];
    }
}
