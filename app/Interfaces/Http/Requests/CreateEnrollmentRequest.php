<?php

namespace App\Interfaces\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEnrollmentRequest extends FormRequest
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
            'student_id' => 'required|integer|exists:students,id',
            'course_id' => 'required|integer|exists:courses,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'El ID del estudiante es requerido',
            'student_id.integer' => 'El ID del estudiante debe ser un número entero',
            'student_id.exists' => 'El estudiante no existe',
            'course_id.required' => 'El ID del curso es requerido',
            'course_id.integer' => 'El ID del curso debe ser un número entero',
            'course_id.exists' => 'El curso no existe',
        ];
    }
} 