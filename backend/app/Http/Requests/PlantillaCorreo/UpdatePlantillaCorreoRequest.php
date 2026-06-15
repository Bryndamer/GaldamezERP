<?php

namespace App\Http\Requests\PlantillaCorreo;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlantillaCorreoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asunto'            => ['required', 'string', 'max:255'],
            'saludo'            => ['nullable', 'string', 'max:255'],
            'cuerpo_principal'  => ['required', 'string', 'max:2000'],
            'cuerpo_secundario' => ['nullable', 'string', 'max:2000'],
            'firma'             => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'asunto.required'           => 'El asunto del correo es obligatorio.',
            'cuerpo_principal.required' => 'El cuerpo principal es obligatorio.',
            'firma.required'            => 'La firma es obligatoria.',
        ];
    }
}
