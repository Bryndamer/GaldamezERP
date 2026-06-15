<?php

namespace App\Http\Requests\Inmueble;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInmuebleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'titulo'       => ['required', 'string', 'max:255'],
            'descripcion'  => ['required', 'string', 'max:5000'],
            'precio'       => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'direccion'    => ['required', 'string', 'max:255'],
            'habitaciones' => ['required', 'integer', 'min:0', 'max:99'],
            'banos'        => ['required', 'integer', 'min:0', 'max:99'],
            'metraje'      => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'estado'       => ['required', 'in:disponible,vendido,reservado'],
            'tipo'         => ['required', 'in:casa,apto,terreno'],
            'category_id'  => ['required', 'exists:categories,id'],
            'fotos'        => ['nullable', 'array', 'max:20'],
            'fotos.*'      => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'precio.min'       => 'El precio debe ser mayor a cero.',
            'fotos.max'        => 'Se permiten máximo 20 fotos por inmueble.',
            'fotos.*.image'    => 'Cada foto debe ser una imagen válida.',
            'fotos.*.mimes'    => 'Las fotos deben estar en formato JPG, PNG o WebP.',
            'fotos.*.max'      => 'Cada foto no puede superar los 10 MB.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
        ];
    }
}
