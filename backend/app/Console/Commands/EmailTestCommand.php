<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class EmailTestCommand extends Command
{
    protected $signature = 'email:test
                            {--to= : Destinatario del correo de prueba (default: MAIL_ADMIN_ADDRESS)}
                            {--queue : Enviar vía cola en vez de envío directo}';

    protected $description = 'Prueba la conexión SMTP y envía un correo de diagnóstico con errores descriptivos';

    public function handle(): int
    {
        $this->newLine();
        $this->line('<fg=cyan;options=bold>══════════════════════════════════════════</>');
        $this->line('<fg=cyan;options=bold>   DIAGNÓSTICO DE CORREO — GaldamezERP   </>');
        $this->line('<fg=cyan;options=bold>══════════════════════════════════════════</>');
        $this->newLine();

        // Paso 1: Mostrar configuración activa
        $this->mostrarConfiguracion();

        // Paso 2: Verificar conectividad TCP
        if (! $this->verificarConexionTcp()) {
            return self::FAILURE;
        }

        // Paso 3: Intentar envío
        return $this->enviarCorreoPrueba();
    }

    private function mostrarConfiguracion(): void
    {
        $password = config('mail.mailers.smtp.password', '');
        $mascarada = $password
            ? str_repeat('*', max(0, strlen($password) - 4)) . substr($password, -4)
            : '(vacío)';

        $this->line('<options=bold>Paso 1 — Configuración activa:</>');
        $this->newLine();

        $this->table([], [
            ['MAILER',     config('mail.default')],
            ['HOST',       config('mail.mailers.smtp.host', '(no definido)')],
            ['PORT',       config('mail.mailers.smtp.port', '(no definido)')],
            ['ENCRYPTION', config('mail.mailers.smtp.encryption', '(no definido)')],
            ['USERNAME',   config('mail.mailers.smtp.username', '(no definido)')],
            ['PASSWORD',   $mascarada],
            ['FROM',       config('mail.from.address', '(no definido)')],
            ['ADMIN',      config('mail.admin_address', '(no definido)')],
            ['QUEUE',      config('queue.default') . ' (queue:work requerido para envíos diferidos)'],
        ]);

        // Advertencias de configuración
        $username = config('mail.mailers.smtp.username', '');
        $from     = config('mail.from.address', '');

        if ($username !== $from) {
            $this->newLine();
            $this->line('<fg=yellow>⚠  ADVERTENCIA:</> MAIL_USERNAME y MAIL_FROM_ADDRESS son distintos.');
            $this->line('   Gmail puede rechazar el envío si el remitente no coincide con la cuenta autenticada.');
            $this->line("   USERNAME: <fg=yellow>{$username}</>");
            $this->line("   FROM:     <fg=yellow>{$from}</>");
        }

        if (! str_contains($username, 'gmail.com') && ! str_contains($username, 'googlemail.com')) {
            $this->newLine();
            $this->line('<fg=yellow>⚠  ADVERTENCIA:</> MAIL_USERNAME no parece ser una cuenta @gmail.com.');
            $this->line('   Las App Passwords de Google solo funcionan con cuentas Gmail o Google Workspace.');
            $this->line('   Si es Google Workspace, asegúrate de que el dominio esté verificado en Google Admin.');
        }

        $this->newLine();
    }

    private function verificarConexionTcp(): bool
    {
        $host = config('mail.mailers.smtp.host', 'smtp.gmail.com');
        $port = (int) config('mail.mailers.smtp.port', 587);

        $this->line('<options=bold>Paso 2 — Verificando conexión de red (TCP):</>');
        $this->newLine();
        $this->line("   Intentando conectar a <fg=white>{$host}:{$port}</> (timeout: 5s)...");

        $errno  = 0;
        $errstr = '';
        $socket = @fsockopen($host, $port, $errno, $errstr, 5);

        if ($socket === false) {
            $this->newLine();
            $this->line("<fg=red>❌  No se pudo establecer conexión TCP a {$host}:{$port}</>");
            $this->line("   Código de error: {$errno} — {$errstr}");
            $this->newLine();
            $this->line('<fg=yellow>   Causas posibles:</>');
            $this->line('   • Tu firewall o antivirus está bloqueando el puerto ' . $port);
            $this->line('   • La red local bloquea conexiones SMTP salientes (común en redes corporativas)');
            $this->line('   • El host MAIL_HOST está mal escrito');
            $this->line('   • Sin acceso a internet');
            $this->newLine();
            $this->line('<fg=yellow>   Prueba manual desde terminal:</>');
            $this->line("   <fg=white>Test-NetConnection -ComputerName {$host} -Port {$port}</>");
            $this->newLine();

            return false;
        }

        fclose($socket);
        $this->line("   <fg=green>✅  Conexión TCP a {$host}:{$port} establecida correctamente.</>");
        $this->newLine();

        return true;
    }

    private function enviarCorreoPrueba(): int
    {
        $destinatario = $this->option('to') ?: config('mail.admin_address');
        $viaQueue     = $this->option('queue');

        if (! $destinatario) {
            $this->line('<fg=red>❌  No se definió destinatario.</> Usa <fg=white>--to=tu@email.com</> o define MAIL_ADMIN_ADDRESS en .env');
            return self::FAILURE;
        }

        $modo = $viaQueue ? 'cola (queue)' : 'directo (sin cola)';
        $this->line("<options=bold>Paso 3 — Enviando correo de prueba ({$modo}):</>");
        $this->newLine();
        $this->line("   Destinatario: <fg=white>{$destinatario}</>");
        $this->line("   Modo:         <fg=white>{$modo}</>");
        $this->newLine();

        $cuerpo = $this->generarCuerpoCorreo($destinatario, $viaQueue);

        try {
            $mailer = Mail::to($destinatario);

            if ($viaQueue) {
                $asunto = '[GaldamezERP] Correo de Diagnóstico — ' . now()->format('d/m/Y H:i:s');
                dispatch(function () use ($destinatario, $cuerpo, $asunto) {
                    Mail::raw($cuerpo, function ($m) use ($destinatario, $asunto) {
                        $m->to($destinatario)->subject($asunto);
                    });
                });
                $this->line('<fg=green>✅  Job encolado correctamente.</>');
                $this->newLine();
                $this->line('<fg=yellow>   Recuerda:</> el correo no se enviará hasta que corras el worker:');
                $this->line('   <fg=white>php artisan queue:work</>');
                $this->newLine();
                $this->line('   Verifica los jobs pendientes:');
                $this->line('   <fg=white>php artisan queue:monitor database</>');
            } else {
                Mail::raw($cuerpo, function ($m) use ($destinatario) {
                    $m->to($destinatario)
                      ->subject('[GaldamezERP] Correo de Diagnóstico — ' . now()->format('d/m/Y H:i:s'));
                });

                $this->line("<fg=green>✅  Correo enviado exitosamente a {$destinatario}</>");
                $this->newLine();
                $this->line('   Revisa la bandeja de <fg=white>entrada</> y <fg=white>spam</> del destinatario.');
            }

            $this->newLine();
            return self::SUCCESS;

        } catch (TransportException $e) {
            return $this->manejarErrorTransporte($e);
        } catch (\Exception $e) {
            $this->line('<fg=red>❌  Error inesperado durante el envío.</>');
            $this->newLine();
            $this->line('   <fg=yellow>Mensaje:</> ' . $e->getMessage());
            $this->line('   <fg=yellow>Clase:</> ' . get_class($e));
            $this->newLine();
            $this->line('   Si el error no es claro, revisa los logs en:');
            $this->line('   <fg=white>storage/logs/laravel.log</>');
            $this->newLine();
            return self::FAILURE;
        }
    }

    private function manejarErrorTransporte(TransportException $e): int
    {
        $mensaje = $e->getMessage();

        $this->line('<fg=red>❌  Error de transporte SMTP.</>');
        $this->newLine();
        $this->line('   <fg=yellow>Mensaje original:</> ' . $mensaje);
        $this->newLine();

        if (str_contains($mensaje, '535') || str_contains($mensaje, 'Username and Password not accepted')) {
            $this->line('<fg=red>   CAUSA:</> Autenticación rechazada por Gmail.');
            $this->newLine();
            $this->line('   <fg=yellow>Qué revisar:</>');
            $this->line('   1. MAIL_USERNAME debe ser exactamente la cuenta Google que generó la App Password');
            $this->line('      Ejemplo: si la App Password la generaste en <fg=white>bryan.rm128@gmail.com</>,');
            $this->line('      entonces MAIL_USERNAME=bryan.rm128@gmail.com');
            $this->line('   2. La App Password son 16 caracteres SIN espacios (Google las muestra con espacios)');
            $this->line('   3. Verifica que la cuenta tenga 2FA activado en myaccount.google.com');
            $this->line('   4. Genera una nueva App Password en: <fg=white>myaccount.google.com/apppasswords</>');

        } elseif (str_contains($mensaje, 'Connection refused') || str_contains($mensaje, 'timed out') || str_contains($mensaje, 'Connection could not be established')) {
            $this->line('<fg=red>   CAUSA:</> No se pudo establecer la conexión SMTP.');
            $this->newLine();
            $this->line('   <fg=yellow>Qué revisar:</>');
            $this->line('   1. Verifica que MAIL_HOST=smtp.gmail.com y MAIL_PORT=587 sean correctos');
            $this->line('   2. Prueba el puerto alternativo SSL: MAIL_PORT=465 + MAIL_ENCRYPTION=ssl');
            $this->line('   3. Revisa el firewall del sistema operativo o del hosting');

        } elseif (str_contains($mensaje, 'SSL') || str_contains($mensaje, 'TLS') || str_contains($mensaje, 'certificate') || str_contains($mensaje, 'STARTTLS')) {
            $this->line('<fg=red>   CAUSA:</> Error de negociación SSL/TLS.');
            $this->newLine();
            $this->line('   <fg=yellow>Qué revisar:</>');
            $this->line('   1. Puerto 587 → MAIL_ENCRYPTION=tls  (STARTTLS)');
            $this->line('   2. Puerto 465 → MAIL_ENCRYPTION=ssl  (SSL nativo)');
            $this->line('   3. Puerto 25  → MAIL_ENCRYPTION=null (sin cifrado, no recomendado)');
            $this->line('   Configuración recomendada para Gmail:');
            $this->line('   <fg=white>MAIL_HOST=smtp.gmail.com');
            $this->line('   MAIL_PORT=587');
            $this->line('   MAIL_ENCRYPTION=tls</>');

        } elseif (str_contains($mensaje, '5.7.0') || str_contains($mensaje, 'relay') || str_contains($mensaje, 'not allowed') || str_contains($mensaje, 'Sender address rejected')) {
            $this->line('<fg=red>   CAUSA:</> Gmail rechazó el remitente.');
            $this->newLine();
            $this->line('   <fg=yellow>Qué revisar:</>');
            $this->line('   1. MAIL_FROM_ADDRESS debe ser la misma cuenta que MAIL_USERNAME');
            $this->line('   2. Gmail no permite enviar como otra dirección salvo que esté verificada en la cuenta');
            $this->line("   MAIL_USERNAME:    <fg=white>" . config('mail.mailers.smtp.username') . "</>");
            $this->line("   MAIL_FROM_ADDRESS: <fg=white>" . config('mail.from.address') . "</>");

        } elseif (str_contains($mensaje, '534') || str_contains($mensaje, '5.7.9') || str_contains($mensaje, 'Application-specific password')) {
            $this->line('<fg=red>   CAUSA:</> Gmail requiere App Password (contraseña de aplicación).');
            $this->newLine();
            $this->line('   <fg=yellow>Pasos para generar una App Password:</>');
            $this->line('   1. Activa la verificación en dos pasos (2FA) en tu cuenta Google');
            $this->line('   2. Ve a: <fg=white>myaccount.google.com/apppasswords</>');
            $this->line('   3. Crea una nueva App Password para "Correo" / "Otro (nombre personalizado)"');
            $this->line('   4. Copia los 16 caracteres SIN espacios en MAIL_PASSWORD del .env');

        } else {
            $this->line('   No se identificó la causa específica del error.');
            $this->line('   Revisa los logs completos en: <fg=white>storage/logs/laravel.log</>');
        }

        $this->newLine();
        return self::FAILURE;
    }

    private function generarCuerpoCorreo(string $destinatario, bool $viaQueue): string
    {
        $modo = $viaQueue ? 'cola (queue)' : 'directo (sin cola)';

        return implode("\n", [
            '══════════════════════════════════════════',
            '  CORREO DE DIAGNÓSTICO — GaldamezERP',
            '══════════════════════════════════════════',
            '',
            'Este correo confirma que la configuración SMTP está funcionando correctamente.',
            '',
            'Detalles del envío:',
            '  • Fecha/hora:   ' . now()->format('d/m/Y H:i:s'),
            '  • Destinatario: ' . $destinatario,
            '  • Modo:         ' . $modo,
            '  • Host SMTP:    ' . config('mail.mailers.smtp.host'),
            '  • Puerto:       ' . config('mail.mailers.smtp.port'),
            '  • Encryption:   ' . config('mail.mailers.smtp.encryption'),
            '  • Usuario SMTP: ' . config('mail.mailers.smtp.username'),
            '  • Remitente:    ' . config('mail.from.address'),
            '',
            'Si recibes este correo, el sistema de envío de emails está configurado correctamente.',
            '',
            '──────────────────────────────────────────',
            'GaldamezERP — Sistema de Diagnóstico',
            'Este es un correo automático de prueba.',
        ]);
    }
}
