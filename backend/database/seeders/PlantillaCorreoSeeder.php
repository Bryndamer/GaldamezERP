<?php

namespace Database\Seeders;

use App\Models\PlantillaCorreo;
use Illuminate\Database\Seeder;

class PlantillaCorreoSeeder extends Seeder
{
    public function run(): void
    {
        PlantillaCorreo::upsert([
            [
                'identificador'     => 'contacto_admin',
                'nombre'            => 'Alerta de nuevo contacto (Admin)',
                'asunto'            => 'Nuevo mensaje de contacto — :nombre',
                'saludo'            => null,
                'cuerpo_principal'  => 'Se ha recibido un nuevo mensaje desde el formulario de contacto del sitio web.',
                'cuerpo_secundario' => 'Revisa los datos del cliente a continuación y contáctalo a la brevedad posible.',
                'firma'             => 'Galdámez ERP — Sistema de Notificaciones',
            ],
            [
                'identificador'     => 'contacto_cliente',
                'nombre'            => 'Confirmación de recepción (Cliente)',
                'asunto'            => 'Hemos recibido tu mensaje — Galdámez',
                'saludo'            => '¡Gracias por contactarnos, :nombre!',
                'cuerpo_principal'  => 'Hemos recibido tu mensaje y nos pondremos en contacto contigo a la brevedad posible.',
                'cuerpo_secundario' => 'Si tienes alguna consulta urgente, puedes comunicarte directamente con nosotros a través de nuestros canales de atención.',
                'firma'             => 'El equipo de Galdámez S.A. de C.V.',
            ],
        ], uniqueBy: ['identificador'], update: [
            'nombre', 'asunto', 'saludo', 'cuerpo_principal', 'cuerpo_secundario', 'firma',
        ]);
    }
}
