<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'sometimes|required|max:100',
            'description' => 'sometimes|required|max:200',
            'detail' => 'sometimes|required|max:200',
            'type' => 'sometimes|required',
            'project_id' => 'sometimes|required|integer',
            'assigned_to' => 'sometimes|required|integer',
            'status' => 'sometimes|required|string',
            'priority' => 'sometimes|required|string',
            'deadline' => 'sometimes|required|date',
            'start_date' => 'sometimes|required|date',



        ];
    }
}
