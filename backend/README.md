# GaldamezERP — Backend (Laravel 12)

API REST y panel administrativo Blade para la plataforma de bienes raíces de Galdámez S.A. de C.V.

## Stack

- **Laravel** 12.12 (PHP 8.2)
- **MySQL** 8 — base de datos principal
- **Laravel Sanctum** 4 — tokens API
- **Laravel Queue** (driver: database) — procesamiento asíncrono
- **PHP GD** nativo — procesamiento de imágenes a WebP
- **Gmail SMTP** — envío de correos con App Password

---

## Setup local

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

El panel admin estará disponible en: **http://localhost:8000/login**

---

## Variables de entorno

Copia `.env.example` a `.env` y rellena estos valores:

| Variable | Descripción |
|---|---|
| `DB_HOST` | Host de MySQL (127.0.0.1 para local o Docker) |
| `DB_PORT` | Puerto de MySQL (3306 por defecto, ajustar para Docker) |
| `DB_DATABASE` | Nombre de la base de datos |
| `DB_USERNAME` / `DB_PASSWORD` | Credenciales MySQL |
| `MAIL_USERNAME` | Cuenta Gmail que generó la App Password |
| `MAIL_PASSWORD` | App Password de 16 caracteres sin espacios |
| `MAIL_FROM_ADDRESS` | Debe coincidir con `MAIL_USERNAME` |
| `MAIL_ADMIN_ADDRESS` | Email donde llegan las notificaciones de contacto |
| `FRONTEND_URL` | URL del frontend React (para CORS) |

---

## Comandos Artisan personalizados

### `php artisan demodata`

Pobla la base de datos con datos de prueba realistas:
- 5 categorías de inmuebles
- 1 admin + 3 agentes (generados con Faker en español)
- 30 inmuebles distribuidos entre los usuarios y categorías
- 20 mensajes de contacto
- 2 plantillas de correo

```bash
php artisan demodata           # Trunca y repobla
php artisan demodata --fresh   # migrate:fresh + repobla
```

**Credenciales de demo:** `admin@galdamez.com` / `password123`

### `php artisan email:test`

Diagnostica la conexión SMTP paso a paso:
1. Muestra la configuración activa
2. Verifica conectividad TCP al servidor SMTP
3. Envía un correo de prueba con mensajes de error descriptivos

```bash
php artisan email:test                        # Envía al MAIL_ADMIN_ADDRESS
php artisan email:test --to=tu@email.com      # Envía a dirección específica
php artisan email:test --queue                # Prueba vía cola
```

---

## Rutas del panel admin (`/admin/*`)

Requieren autenticación y rol `admin`.

| Ruta | Descripción |
|---|---|
| `GET /admin/dashboard` | Dashboard principal |
| `GET /admin/inmuebles` | CRUD de inmuebles |
| `GET /admin/categorias` | CRUD de categorías |
| `GET /admin/users` | CRUD de usuarios |
| `GET /admin/mensajes` | Bandeja de mensajes de contacto |
| `POST /admin/mensajes/{id}/reenviar` | Reenviar correos de un mensaje |
| `GET /admin/plantillas` | Editar plantillas de correo |

---

## API REST pública (`/api/*`)

Sin autenticación. Rate limiting en rutas de contacto.

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/api/inmuebles` | Listado paginado (filtros: precio, tipo, categoría, habitaciones) |
| GET | `/api/inmuebles/{id}` | Detalle de inmueble |
| GET | `/api/categorias` | Lista de categorías |
| POST | `/api/mensajes` | Formulario de contacto (máx. 5 por hora por IP) |

---

## Autenticación

- **Panel admin**: sesiones Laravel nativas (`/login`), CSRF automático.
- **API pública**: sin autenticación (solo rate limiting).
- **API futura (agentes)**: Sanctum tokens (`/api/sanctum/token`).

### Roles

| Rol | Acceso |
|---|---|
| `admin` | Panel completo, todos los inmuebles |
| `agente` | Solo sus propios inmuebles |

---

## Modelos y base de datos

| Tabla | Modelo | Descripción |
|---|---|---|
| `users` | `User` | Usuarios con role (admin/agente) y phone |
| `categories` | `Category` | Categorías con slug único |
| `inmuebles` | `Inmueble` | Propiedades con fotos JSON (hasta 20) |
| `mensajes` | `Mensaje` | Contactos del formulario público |
| `plantillas_correo` | `PlantillaCorreo` | Plantillas editables de email |
| `jobs` | — | Cola de trabajos (Laravel Queue) |

---

## Tests

```bash
php artisan test
```

- **15 tests** cubriendo autenticación, RBAC, CRUD de inmuebles, API pública y rate limiting.
- Base de datos: SQLite en memoria (sin afectar la BD de desarrollo).

---

## Principios de desarrollo

1. Validación solo en **Form Requests** — nunca inline en controladores.
2. **RBAC nativo** — Gates y Middlewares propios, prohibido Spatie.
3. Imágenes: **PHP GD** → WebP, máx. 1920px, calidad 85.
4. **API Resources** para todas las respuestas JSON.
5. **CSRF** en todos los formularios Blade.
6. Rate limiting: login 5/min, contacto 5/hora por IP.
