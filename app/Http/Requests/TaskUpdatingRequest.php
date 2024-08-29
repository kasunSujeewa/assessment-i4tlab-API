<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use App\Traits\CustomValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskUpdatingRequest extends FormRequest
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
            'title' => 'string',
            'user_id' => 'exists:users,id',
            'worker_id' => 'exists:users,id',
            'status' => new Enum(TaskStatus::class)
        ];
    }
}
