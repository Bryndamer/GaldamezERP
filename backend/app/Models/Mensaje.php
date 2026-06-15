<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mensaje extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'mensaje',
        'inmueble_id',
        'tipo',
        'leido',
    ];

    protected function casts(): array
    {
        return [
            'leido'      => 'boolean',
            'inmueble_id' => 'integer',
        ];
    }

    public function inmueble(): BelongsTo
    {
        return $this->belongsTo(Inmueble::class);
    }
}
