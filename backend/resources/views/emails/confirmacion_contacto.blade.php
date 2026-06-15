<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $plantilla->asunto }}</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 0; }
        .wrapper { padding: 32px 16px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%); padding: 36px 40px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px; }
        .header p { color: #bfdbfe; margin: 8px 0 0; font-size: 13px; }
        .check-icon { width: 56px; height: 56px; background: rgba(255,255,255,0.15); border-radius: 50%; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 20px; font-weight: 700; color: #111827; margin: 0 0 8px; }
        .lead { font-size: 15px; color: #374151; line-height: 1.6; margin: 0 0 16px; }
        .secondary { font-size: 14px; color: #6b7280; line-height: 1.6; margin: 0 0 28px; }
        .divider { border: none; border-top: 1px solid #f3f4f6; margin: 28px 0; }
        .summary-title { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 16px; }
        .summary-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px 24px; }
        .row { display: flex; gap: 8px; margin-bottom: 12px; }
        .row:last-child { margin-bottom: 0; }
        .row-label { font-size: 12px; font-weight: 600; color: #6b7280; min-width: 90px; padding-top: 2px; }
        .row-value { font-size: 14px; color: #111827; flex: 1; line-height: 1.5; }
        .message-block { background: #eff6ff; border-left: 3px solid #3b82f6; border-radius: 0 8px 8px 0; padding: 14px 16px; margin-top: 4px; font-size: 14px; color: #1e3a5f; line-height: 1.6; white-space: pre-wrap; }
        .footer { border-top: 1px solid #f3f4f6; padding: 24px 40px; background: #f9fafb; text-align: center; }
        .footer .firma { font-size: 14px; font-weight: 600; color: #374151; margin: 0 0 4px; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; line-height: 1.6; }
    </style>
</head>
<body>
<div class="wrapper">
<div class="container">

    <div class="header">
        <div class="check-icon">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1>Galdámez S.A. de C.V.</h1>
        <p>Bienes Raíces</p>
    </div>

    <div class="body">

        @if($plantilla->saludo)
        <p class="greeting">{{ str_replace(':nombre', $mensaje->nombre, $plantilla->saludo) }}</p>
        @endif

        <p class="lead">{{ $plantilla->cuerpo_principal }}</p>

        @if($plantilla->cuerpo_secundario)
        <p class="secondary">{{ $plantilla->cuerpo_secundario }}</p>
        @endif

        <hr class="divider">

        <p class="summary-title">Resumen de tu solicitud</p>

        <div class="summary-box">

            <div class="row">
                <span class="row-label">Nombre</span>
                <span class="row-value">{{ $mensaje->nombre }}</span>
            </div>

            <div class="row">
                <span class="row-label">Correo</span>
                <span class="row-value">{{ $mensaje->email }}</span>
            </div>

            @if($mensaje->telefono)
            <div class="row">
                <span class="row-label">Teléfono</span>
                <span class="row-value">{{ $mensaje->telefono }}</span>
            </div>
            @endif

            <div class="row" style="flex-direction: column;">
                <span class="row-label" style="margin-bottom:8px;">Mensaje</span>
                <div class="message-block">{{ $mensaje->mensaje }}</div>
            </div>

            @if($mensaje->inmueble)
            <div class="row" style="margin-top:12px;">
                <span class="row-label">Inmueble</span>
                <span class="row-value">
                    {{ $mensaje->inmueble->titulo }}
                    — ${{ number_format($mensaje->inmueble->precio, 2) }}
                </span>
            </div>
            @endif

        </div>

    </div>

    <div class="footer">
        <p class="firma">{{ $plantilla->firma }}</p>
        <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        <p style="margin-top:6px;">&copy; {{ date('Y') }} Galdámez S.A. de C.V. — Todos los derechos reservados.</p>
        <p style="margin-top:4px;">
            developed by
            <a href="https://portafolio-layout.vercel.app/Portafolioindex.html"
               style="color:#3b82f6; text-decoration:none;">Danilo Rauda</a>
            &amp; Galdámez S.A. de C.V. | powered by WebExperience &copy; 2026
        </p>
    </div>

</div>
</div>
</body>
</html>
