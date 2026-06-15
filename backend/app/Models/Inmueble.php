<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Inmueble extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_id',
        'titulo',
        'descripcion',
        'precio',
        'direccion',
        'habitaciones',
        'banos',
        'metraje',
        'estado',
        'tipo',
        'fotos',
    ];

    protected function casts(): array
    {
        return [
            'precio'       => 'decimal:2',
            'metraje'      => 'decimal:2',
            'habitaciones' => 'integer',
            'banos'        => 'integer',
            'fotos'        => 'array',
        ];
    }

    protected static function booted(): void
    {
        // Eliminar archivos físicos al borrar el registro
        static::deleting(function (self $inmueble): void {
            if (! empty($inmueble->fotos)) {
                foreach ($inmueble->fotos as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
        });
    }

    public function agente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
