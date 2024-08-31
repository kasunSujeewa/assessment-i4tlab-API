<?php

namespace App\Http\Requests;

use App\Traits\CustomValidationResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class TaskStoringRequest extends FormRequest
{
    use CustomValidationResponseTrait;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'due_date' => 'date_format:Y-m-d'
        ];
    }
}
