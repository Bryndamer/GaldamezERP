<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmacionContacto;
use App\Mail\NuevoMensajeContacto;
use App\Models\Mensaje;
use App\Models\PlantillaCorreo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class MensajeController extends Controller
{
    public function index(): View
    {
        $mensajes = Mensaje::with('inmueble')
            ->latest()
            ->paginate(10);

        return view('admin.mensajes.index', compact('mensajes'));
    }

    public function markRead(Mensaje $mensaje): RedirectResponse
    {
        $mensaje->update(['leido' => ! $mensaje->leido]);

        return back()->with('success', $mensaje->leido ? 'Marcado como leído.' : 'Marcado como no leído.');
    }

    public function reenviarCorreos(Mensaje $mensaje): RedirectResponse
    {
        $plantillaAdmin   = PlantillaCorreo::porIdentificador('contacto_admin');
        $plantillaCliente = PlantillaCorreo::porIdentificador('contacto_cliente');

        if (! $plantillaAdmin || ! $plantillaCliente) {
            return redirect()
                ->route('admin.mensajes.index')
                ->with('error', 'No se encontraron las plantillas de correo en la base de datos. Ejecuta "php artisan demodata" o crea las plantillas desde el panel de Plantillas.');
        }

        try {
            Mail::to(config('mail.admin_address'))
                ->send(new NuevoMensajeContacto($mensaje, $plantillaAdmin));

            Mail::to($mensaje->email)
                ->send(new ConfirmacionContacto($mensaje, $plantillaCliente));

        } catch (\Throwable $e) {
            Log::error('Error al reenviar correos desde panel admin', [
                'mensaje_id' => $mensaje->id,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('admin.mensajes.index')
                ->with('error', 'Error al enviar correos: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.mensajes.index')
            ->with('success', "Correos reenviados a {$mensaje->email} y al administrador.");
    }

    public function destroy(Mensaje $mensaje): RedirectResponse
    {
        $mensaje->delete();

        return redirect()->route('admin.mensajes.index')
            ->with('success', 'Mensaje eliminado.');
    }
}
