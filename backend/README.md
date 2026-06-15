# GaldamezERP — Backend (Laravel 12)

API REST y panel administrativo Blade para la plataforma de bienes raíces de Galdámez S.A. de C.V.

## Stack

- **Laravel** 12.12 (PHP 8.2)
- **MySQL** 8 — base de datos principal
- **Laravel Sanctum** 4 — tokens API
- **Laravel Queue** (driver: database) — procesamiento asíncrono
- **PHP GD** nativo — procesamiento de imágenes a WebP
- **Gmail SMTP** — envío de correos con App Password (envío directo, sin cola)

---

## Setup con Docker (recomendado)

Desde la raíz del monorepo:

```bash
docker compose up --build
```

El contenedor incluye PHP-FPM + Nginx + Supervisor. Al iniciar, el entrypoint automaticamente:
1. Espera a que MySQL esté listo
2. Ejecuta `php artisan migrate --force`
3. Crea el symlink de storage
4. Lanza PHP-FPM y Nginx via supervisord

**Primera vez — poblar con datos de prueba:**

```bash
docker compose exec backend php artisan demodata
```

Panel admin disponible en: **http://localhost:8000/login**

---

## Setup local (sin Docker)

```bash
# 1. Instalar dependencias PHP
composer install

# 2. Copiar y configurar variables de entorno
cp .env.example .env
php artisan key:generate

# 3. Editar .env con tus credenciales de MySQL y Gmail
#    Ver sección "Variables de entorno" más abajo

# 4. Ejecutar migraciones
php artisan migrate

# 5. Poblar con datos de prueba
php artisan demodata

# 6. Enlazar almacenamiento público (imágenes)
php artisan storage:link

# 7. Levantar servidor
php artisan serve
```

---

## Variables de entorno

Copia `.env.example` a `.env` y rellena estos valores:

| Variable | Descripción |
|---|---|
| `DB_HOST` | `127.0.0.1` en local · `mysql` en Docker |
| `DB_PORT` | `3306` estándar · `3309` si usas Docker mapeado al host |
| `DB_DATABASE` | `GaldamezERP` |
| `DB_USERNAME` / `DB_PASSWORD` | Credenciales MySQL |
| `MAIL_USERNAME` | Cuenta Google que generó la App Password |
| `MAIL_PASSWORD` | App Password de 16 caracteres **sin espacios** |
| `MAIL_FROM_ADDRESS` | Debe coincidir exactamente con `MAIL_USERNAME` |
| `MAIL_ADMIN_ADDRESS` | Email donde llegan las notificaciones de contacto |
| `FRONTEND_URL` | `http://localhost:5173` (para CORS) |

> En Docker, las variables de BD (`DB_*`) se inyectan desde `docker-compose.yml` y sobreescriben el `.env`. Solo necesitas configurar las de correo.

---

## Comandos Artisan personalizados

### `php artisan demodata`

Pobla la base de datos con datos de prueba realistas en español:
- 5 categorías de inmuebles (Casa, Apartamento, Terreno, Local Comercial, Bodega)
- 1 admin + 1 agente fijos + 2 agentes generados con Faker
- 30 inmuebles con títulos y direcciones salvadoreñas
- 20 mensajes de contacto con datos faker
- 2 plantillas de correo (upsert idempotente)

```bash
php artisan demodata              # Trunca tablas y repobla
php artisan demodata --fresh      # migrate:fresh + repobla desde cero

# Con Docker:
docker compose exec backend php artisan demodata
docker compose exec backend php artisan demodata --fresh
```

**Credenciales de demo:** `admin@galdamez.com` / `password123`

### `php artisan email:test`

Diagnostica la conexión SMTP en 3 pasos con errores descriptivos en español:
1. Muestra la configuración activa (contraseña enmascarada)
2. Verifica conectividad TCP al host SMTP (detecta problemas de firewall/red)
3. Envía un correo de prueba con mensajes de error específicos por tipo de fallo

Errores que identifica con su causa y solución:
- `535 Username and Password not accepted` → App Password incorrecta o 2FA no activado
- `Connection refused / timed out` → Firewall bloqueando puerto 587
- `SSL/TLS error` → Configuración de cifrado incorrecta
- `Sender address rejected` → `MAIL_FROM_ADDRESS` no coincide con `MAIL_USERNAME`

```bash
php artisan email:test                        # Envía al MAIL_ADMIN_ADDRESS
php artisan email:test --to=tu@email.com      # Envía a dirección específica
php artisan email:test --queue                # Prueba vía cola (requiere queue:work)

# Con Docker:
docker compose exec backend php artisan email:test --to=tu@email.com
```

---

## Módulo de correo

Los correos se envían de forma **directa** (`Mail::send()`) desde los controladores, sin depender de la cola de trabajos. Esto garantiza que el envío ocurra inmediatamente y que cualquier error sea visible.

| Clase | Destinatario | Plantilla |
|---|---|---|
| `NuevoMensajeContacto` | Admin (`MAIL_ADMIN_ADDRESS`) | `emails.nuevo_mensaje` |
| `ConfirmacionContacto` | Cliente (quien envió el formulario) | `emails.confirmacion_contacto` |

Las plantillas son editables desde el panel admin en `/admin/plantillas`. Soportan el token `:nombre` que se reemplaza por el nombre del contactante.

> Si el envío falla, el error completo (con stack trace) se registra en `storage/logs/laravel.log`.

---

## Rutas del panel admin (`/admin/*`)

Requieren autenticación y rol `admin`.

| Ruta | Descripción |
|---|---|
| `GET /admin/dashboard` | Dashboard principal |
| `GET /admin/inmuebles` | CRUD de inmuebles (con subida de imágenes WebP) |
| `GET /admin/categorias` | CRUD de categorías |
| `GET /admin/users` | CRUD de usuarios con roles |
| `GET /admin/mensajes` | Bandeja de mensajes de contacto |
| `POST /admin/mensajes/{id}/reenviar` | Reenviar correos de un mensaje existente |
| `GET /admin/plantillas` | Editar plantillas de correo |

---

## API REST pública (`/api/*`)

Sin autenticación. Rate limiting en rutas de contacto (5 por hora por IP).

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/api/inmuebles` | Listado paginado (filtros: precio\_min/max, categoria\_id, tipo, habitaciones\_min) |
| GET | `/api/inmuebles/{id}` | Detalle de inmueble |
| GET | `/api/categorias` | Lista de categorías |
| POST | `/api/mensajes` | Formulario de contacto |

Las mismas rutas están disponibles con prefijo `/api/v1/` para retrocompatibilidad.

---

## Autenticación

- **Panel admin**: sesiones Laravel nativas (`/login`), CSRF automático.
- **API pública**: sin autenticación (solo rate limiting).
- **API futura (agentes)**: Sanctum tokens (`/api/sanctum/token`).

### Roles

| Rol | Acceso |
|---|---|
| `admin` | Panel completo, todos los inmuebles |
| `agente` | Solo sus propios inmuebles (Gate `manage-inmueble`) |

---

## Modelos y base de datos

| Tabla | Modelo | Descripción |
|---|---|---|
| `users` | `User` | Roles (admin/agente), phone, HasApiTokens |
| `categories` | `Category` | name + slug únicos, HasFactory |
| `inmuebles` | `Inmueble` | Fotos JSON (hasta 20), HasFactory, borra archivos al eliminar |
| `mensajes` | `Mensaje` | Contactos del formulario, HasFactory |
| `plantillas_correo` | `PlantillaCorreo` | Plantillas editables de email |
| `jobs` | — | Cola de trabajos (Laravel Queue) |

---

## Docker — estructura interna del contenedor

```
backend/
├── Dockerfile              # PHP 8.2-FPM Alpine + Nginx + Node + Composer
└── docker/
    ├── nginx.conf          # Nginx en puerto 8000, proxy a PHP-FPM:9000
    ├── supervisord.conf    # Corre php-fpm y nginx simultáneamente
    └── entrypoint.sh       # Espera MySQL → migrate → storage:link → supervisord
```

El contenedor expone únicamente el puerto `8000`. PHP-FPM corre internamente en `127.0.0.1:9000` y Nginx actúa como proxy.

---

## Tests

```bash
php artisan test                    # Todos los tests
php artisan test --filter="Api"     # Solo tests de API

# Con Docker:
docker compose exec backend php artisan test
```

- **15 tests** — autenticación, RBAC, CRUD de inmuebles, API pública, rate limiting.
- BD de test: SQLite en memoria (no afecta la BD de desarrollo).

---

## Principios de desarrollo

1. Validación solo en **Form Requests** — nunca inline en controladores.
2. **RBAC nativo** — Gates y Middlewares propios, prohibido Spatie.
3. Imágenes: **PHP GD** → WebP, máx. 1920px, calidad 85.
4. **API Resources** para todas las respuestas JSON.
5. **CSRF** en todos los formularios Blade.
6. Rate limiting: login 5/min, contacto 5/hora por IP.
7. Correos: envío directo `Mail::send()` — sin dependencia de `queue:work`.
