<?php

namespace App\Http\Requests;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTaskRequest extends FormRequest
{
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
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|date_format:Y-m-d',
            'status' => 'in:todo,in_progress,done',
            'priority' => 'in:low,medium,high',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = (new BaseController)->sendErrorJson(
            'Validation Error',
            $validator->errors(),
            422
        );
        throw new HttpResponseException($response);
    }
}
