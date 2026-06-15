<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ContactoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'nombre'      => ['required', 'string', 'min:2', 'max:100'],
            'email'       => ['required', 'email:rfc,dns', 'max:255'],
            'telefono'    => ['nullable', 'string', 'regex:/^[+\d\s\-\(\)]{7,20}$/'],
            'mensaje'     => ['required', 'string', 'min:10', 'max:2000'],
            'inmueble_id' => ['nullable', 'integer', 'exists:inmuebles,id'],
            'tipo'        => ['required', 'in:contacto,venta'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.min'      => 'El nombre debe tener al menos 2 caracteres.',
            'email.email'     => 'Ingresa un correo electrónico válido.',
            'telefono.regex'  => 'El formato de teléfono no es válido.',
            'mensaje.min'     => 'El mensaje debe tener al menos 10 caracteres.',
            'inmueble_id.exists' => 'El inmueble referenciado no existe.',
            'tipo.in'         => 'El tipo debe ser "contacto" o "venta".',
        ];
    }

    // Las APIs deben devolver JSON en errores de validación, no redirects
    protected function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Los datos enviados no son válidos.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
