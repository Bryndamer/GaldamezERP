<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class InmuebleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'titulo'       => $this->titulo,
            'descripcion'  => $this->descripcion,
            'precio'       => (float) $this->precio,
            'direccion'    => $this->direccion,
            'habitaciones' => $this->habitaciones,
            'banos'        => $this->banos,
            'metraje'      => (float) $this->metraje,
            'estado'       => $this->estado,
            'tipo'         => $this->tipo,
            // URLs absolutas de las fotos WebP para el frontend React
            'fotos'        => collect($this->fotos ?? [])->map(
                fn (string $path) => Storage::disk('public')->url($path)
            )->values(),
            'categoria'    => new CategoryResource($this->whenLoaded('category')),
            'agente'       => $this->when(
                $this->relationLoaded('agente'),
                fn () => [
                    'id'   => $this->agente->id,
                    'name' => $this->agente->name,
                ]
            ),
            'creado_en'    => $this->created_at->toISOString(),
        ];
    }
}
