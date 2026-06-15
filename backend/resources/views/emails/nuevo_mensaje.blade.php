<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo mensaje de contacto</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #1d4ed8; padding: 28px 32px; }
        .header h1 { color: #ffffff; margin: 0; font-size: 20px; font-weight: 700; }
        .header p { color: #bfdbfe; margin: 6px 0 0; font-size: 13px; }
        .body { padding: 32px; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 20px; }
        .badge-contacto { background: #dbeafe; color: #1d4ed8; }
        .badge-venta { background: #dcfce7; color: #166534; }
        .field { margin-bottom: 16px; }
        .field-label { font-size: 12px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .field-value { font-size: 15px; color: #111827; }
        .mensaje-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; margin: 20px 0; font-size: 14px; color: #374151; line-height: 1.6; white-space: pre-wrap; }
        .inmueble-ref { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px 16px; margin: 16px 0; font-size: 13px; color: #1e40af; }
        .footer { border-top: 1px solid #e5e7eb; padding: 20px 32px; background: #f9fafb; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
        hr { border: none; border-top: 1px solid #e5e7eb; margin: 20px 0; }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <h1>Galdámez ERP</h1>
        <p>Nuevo mensaje de contacto recibido</p>
    </div>

    <div class="body">

        <p style="font-size:14px; color:#374151; margin:0 0 20px;">{{ $plantilla->cuerpo_principal }}</p>

        @if($plantilla->cuerpo_secundario)
        <p style="font-size:14px; color:#6b7280; margin:0 0 20px;">{{ $plantilla->cuerpo_secundario }}</p>
        @endif

        <span class="badge badge-{{ $mensaje->tipo }}">
            {{ $mensaje->tipo === 'venta' ? 'Interesado en compra' : 'Consulta general' }}
        </span>

        <div class="field">
            <div class="field-label">Nombre</div>
            <div class="field-value">{{ $mensaje->nombre }}</div>
        </div>

        <div class="field">
            <div class="field-label">Correo electrónico</div>
            <div class="field-value">
                <a href="mailto:{{ $mensaje->email }}" style="color:#1d4ed8;">{{ $mensaje->email }}</a>
            </div>
        </div>

        @if($mensaje->telefono)
        <div class="field">
            <div class="field-label">Teléfono</div>
            <div class="field-value">{{ $mensaje->telefono }}</div>
        </div>
        @endif

        <hr>

        <div class="field-label">Mensaje</div>
        <div class="mensaje-box">{{ $mensaje->mensaje }}</div>

        @if($mensaje->inmueble)
        <div class="inmueble-ref">
            Inmueble de referencia:
            <strong>{{ $mensaje->inmueble->titulo }}</strong>
            — ${{ number_format($mensaje->inmueble->precio, 2) }}
        </div>
        @endif

    </div>

    <div class="footer">
        <p>{{ $plantilla->firma }}</p>
        <p style="margin-top:4px;">Mensaje recibido el {{ $mensaje->created_at->format('d/m/Y \a \l\a\s H:i') }}</p>
        <p style="margin-top:4px;">Para responder, usa Reply directamente a este correo.</p>
        <p style="margin-top:4px;">
            developed by
            <a href="https://portafolio-layout.vercel.app/Portafolioindex.html"
               style="color:#3b82f6; text-decoration:none;">Danilo Rauda</a>
            &amp; Galdámez S.A. de C.V. | powered by WebExperience &copy; 2026
        </p>
    </div>

</div>
</body>
</html>
