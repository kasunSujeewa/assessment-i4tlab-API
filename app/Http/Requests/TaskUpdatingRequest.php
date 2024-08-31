<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use App\Traits\CustomValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskUpdatingRequest extends FormRequest
{
    use CustomValidationResponseTrait;
    
    public function authorize(): bool
    {
        return true;
    }

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
