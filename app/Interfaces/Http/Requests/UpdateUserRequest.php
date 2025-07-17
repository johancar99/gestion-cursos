<?php

namespace App\Interfaces\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');
        
        return [
            'name' => 'sometimes|string|min:2|max:255',
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|string|in:admin,secretary',
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'El nombre debe tener al menos 2 caracteres',
            'name.max' => 'El nombre no puede exceder 255 caracteres',
            'email.email' => 'El formato del email no es válido',
            'email.unique' => 'El email ya está registrado',
            'email.max' => 'El email no puede exceder 255 caracteres',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'role.in' => 'El rol debe ser admin o secretary',
        ];
    }
} 