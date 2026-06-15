<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactoRequest;
use App\Mail\ConfirmacionContacto;
use App\Mail\NuevoMensajeContacto;
use App\Models\Mensaje;
use App\Models\PlantillaCorreo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MensajeApiController extends Controller
{
    public function store(ContactoRequest $request): JsonResponse
    {
        $mensaje = Mensaje::create($request->validated());

        try {
            $plantillaAdmin   = PlantillaCorreo::porIdentificador('contacto_admin');
            $plantillaCliente = PlantillaCorreo::porIdentificador('contacto_cliente');

            Mail::to(config('mail.admin_address'))
                ->send(new NuevoMensajeContacto($mensaje, $plantillaAdmin));

            Mail::to($mensaje->email)
                ->send(new ConfirmacionContacto($mensaje, $plantillaCliente));

        } catch (\Throwable $e) {
            Log::error('Error al enviar correos de contacto', [
                'mensaje_id' => $mensaje->id,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
        }

        return response()->json([
            'message' => 'Tu mensaje ha sido recibido. Un agente te contactará pronto.',
            'id'      => $mensaje->id,
        ], 201);
    }
}
