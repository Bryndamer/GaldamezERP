<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantillaCorreo extends Model
{
    protected $table = 'plantillas_correo';

    protected $fillable = [
        'identificador',
        'nombre',
        'asunto',
        'saludo',
        'cuerpo_principal',
        'cuerpo_secundario',
        'firma',
    ];

    public static function porIdentificador(string $id): self
    {
        return static::where('identificador', $id)->firstOrFail();
    }
}
