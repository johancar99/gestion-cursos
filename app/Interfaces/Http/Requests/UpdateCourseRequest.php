<?php

namespace App\Interfaces\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $courseId = $this->route('course');
        
        return [
            'title' => 'sometimes|string|min:2|max:255',
            'description' => 'sometimes|string|min:10|max:1000',
            'start_date' => 'sometimes|date|after_or_equal:today',
            'end_date' => 'sometimes|date|after:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'title.min' => 'El título debe tener al menos 2 caracteres',
            'title.max' => 'El título no puede exceder 255 caracteres',
            'description.min' => 'La descripción debe tener al menos 10 caracteres',
            'description.max' => 'La descripción no puede exceder 1000 caracteres',
            'start_date.date' => 'El formato de fecha de inicio no es válido',
            'start_date.after_or_equal' => 'La fecha de inicio debe ser hoy o posterior',
            'end_date.date' => 'El formato de fecha de fin no es válido',
            'end_date.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
        ];
    }
} 