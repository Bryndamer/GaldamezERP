# Backend — Estado de Avance

**Última actualización:** 2026-05-29
**Versión Laravel:** 12.12.2 (PHP 8.2)
**Base de datos:** MySQL — `galdamez_erp`
**Servidor local:** `php artisan serve` → http://localhost:8000
**Tests:** ✓ 15/15 PHPUnit — `php artisan test --filter="Api"`

---

## Fases Completadas

### Fase 1 — Setup, Base de Datos y Modelos ✅

**Migraciones ejecutadas (`migrate:fresh --seed`):**

| Tabla | Descripción |
|---|---|
| `users` | Extendida con `role enum('admin','agente')` y `phone varchar(20) nullable` |
| `categories` | `name` (unique), `slug` (unique) |
| `inmuebles` | titulo, descripcion, precio, tipo, estado, habitaciones, banos, metraje, direccion, fotos (JSON), user_id FK, category_id FK. Índices en precio, tipo, estado |
| `mensajes` | nombre, email (indexed), telefono nullable, mensaje text, inmueble_id nullable FK (nullOnDelete), tipo enum(contacto/venta), leido boolean (indexed) |

**Modelos:**
- `User` — HasApiTokens, helpers `isAdmin()` / `isAgente()`, hasMany(Inmueble)
- `Inmueble` — fotos cast array, `booted()` deleting event limpia Storage
- `Category` — fillable: name/slug, hasMany(Inmueble)
- `Mensaje` — fillable completo, belongsTo(Inmueble) nullable

---

### Fase 2 — Seguridad y RBAC ✅

- `app/Http/Middleware/CheckRole.php` — Alias `role`, acepta parámetro (`role:admin`, `role:agente`)
- `app/Http/Controllers/Auth/AuthController.php` — `login()`, `authenticate()`, `logout()`
- `resources/views/auth/login.blade.php` — Tailwind, @csrf
- Gate `manage-inmueble`: admin = all, agente = own
- RateLimiter `login`: 5/min por IP+email
- RateLimiter `contacto`: 5/hora por IP (JSON response)

---

### Fase 3a — Seeder y Dashboards ✅

**Usuarios de prueba:**

| Rol | Email | Password |
|---|---|---|
| Admin | admin@galdamez.com | password123 |
| Agente | agente@galdamez.com | password123 |

**Categorías sembradas:** Casa, Apartamento, Terreno, Local Comercial

---

### Fase 3b — ImageService y CRUD de Inmuebles ✅

- `app/Services/ImageService.php` — GD nativo → WebP, `MAX_WIDTH=1920`, `WEBP_QUALITY=85`, PNG alpha
- `InmuebleController` — Resource completo, Gate en edit/update/destroy
- Form Requests: `StoreInmuebleRequest` / `UpdateInmuebleRequest` — fotos: nullable array max:20, 10MB c/u
- Vistas Blade: `admin/inmuebles/` — index, create, edit, `_form.blade.php`

---

### Fase 4 — API REST y Módulo de Mensajes ✅

**API Resources:** `InmuebleResource`, `CategoryResource`

**Controladores API:**
- `Api/PublicInmuebleController` — index (paginado, disponibles) + show + categorias
  - Filtros: precio_min, precio_max, categoria_id, tipo, habitaciones_min
- `Api/MensajeApiController` — store con try/catch mail
- `Api/ContactoRequest` — `failedValidation()` retorna JSON 422

---

### Fase 5 — Panel Admin Completo ✅

**Controladores admin:**
- `CategoryController` — destroy protege si tiene inmuebles
- `UserController` — destroy verifica no sea el usuario actual
- `MensajeController` — markRead (PATCH toggle `leido`), destroy, **reenviarCorreos** (Mail::send sync)

**Layout admin:** sidebar 256px (`bg-gray-900`) + Alpine.js, badge no leídos, alertas centralizadas

**Total rutas web:** 22 rutas en grupos por middleware

---

### Fase 6 — API Pública + Frontend React Setup ✅

**`routes/api.php` — rutas activas:**

```
GET  /api/v1/inmuebles          → PublicInmuebleController@index
GET  /api/v1/inmuebles/{id}     → PublicInmuebleController@show
GET  /api/v1/categorias         → PublicInmuebleController@categorias
POST /api/v1/contacto           → ContactoController@store (throttle:contacto)

GET  /api/inmuebles             → (mismo, retrocompatibilidad)
GET  /api/inmuebles/{id}        → (mismo)
GET  /api/categorias            → (mismo)
POST /api/mensajes              → MensajeApiController@store
```

---

### Cambio: Capacidad de Galería ✅ (2026-05-11)

Límite de fotos expandido de 5 a 20 por inmueble.

| Archivo | Cambio |
|---|---|
| `StoreInmuebleRequest.php` / `UpdateInmuebleRequest.php` | `max:5` → `max:20` |
| `InmuebleController::store()` / `update()` | `memory_limit=256M`, `max_execution_time=120` |
| `_form.blade.php` | Texto de ayuda actualizado |
| `PropiedadDetalleView.jsx` | Thumbnails con `flex-wrap` |

---

### Módulo de Correo — Notificaciones de Doble Vía ✅ (2026-05-29)

**Objetivo:** correo de confirmación al cliente + textos editables desde panel admin.

**Nuevos archivos:**

| Archivo | Descripción |
|---|---|
| `database/migrations/2026_05_29_..._create_plantillas_correo_table.php` | Tabla `plantillas_correo` |
| `database/seeders/PlantillaCorreoSeeder.php` | Valores por defecto (upsert, idempotente) |
| `app/Models/PlantillaCorreo.php` | Modelo + helper `porIdentificador(string $id)` |
| `app/Mail/ConfirmacionContacto.php` | Mailable ShouldQueue → cliente |
| `resources/views/emails/confirmacion_contacto.blade.php` | Email HTML al cliente |
| `app/Http/Requests/PlantillaCorreo/UpdatePlantillaCorreoRequest.php` | Validación |
| `app/Http/Controllers/PlantillaCorreoController.php` | index / edit / update |
| `resources/views/admin/plantillas/index.blade.php` | Lista de plantillas |
| `resources/views/admin/plantillas/edit.blade.php` | Formulario de edición con token `:nombre` |

**Archivos modificados:**

| Archivo | Cambio |
|---|---|
| `app/Mail/NuevoMensajeContacto.php` | Inyecta `PlantillaCorreo` para asunto y cuerpo |
| `resources/views/emails/nuevo_mensaje.blade.php` | Usa variables de plantilla |
| `app/Http/Controllers/Api/ContactoController.php` | Despacha 2 correos (admin + cliente) en try/catch |
| `routes/web.php` | Rutas `admin.plantillas` + `mensajes.reenviar` |
| `resources/views/layouts/admin.blade.php` | Link "Plantillas de Correo" en sidebar; crédito "developed by Danilo Rauda" en footer |

**Flujo:**
- `POST /api/v1/contacto` → guarda Mensaje → encola 2 correos (fallo silencioso)
- `POST /admin/mensajes/{id}/reenviar` → `Mail::send()` sincrónico, muestra error SMTP si falla
- `GET /admin/plantillas` → lista editable de las 2 plantillas
- Token `:nombre` disponible en asunto y saludo

**Config SMTP (.env):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=bryan.rm128@gmail.com
MAIL_PASSWORD=uifuxdrileqickwm
MAIL_FROM_ADDRESS="bryan.rm128@gmail.com"
MAIL_FROM_NAME="Galdámez S.A. de C.V."
MAIL_ADMIN_ADDRESS="bryan.rm128@gmail.com"
QUEUE_CONNECTION=database
```

---

### Suite de Tests PHPUnit ✅ (2026-05-29)

**Config:** `phpunit.xml` — SQLite `:memory:`, `MAIL_MAILER=array`, `QUEUE_CONNECTION=sync`

**Tests creados en `tests/Feature/Api/`:**

| Archivo | Tests | Qué valida |
|---|---|---|
| `InmueblesApiTest.php` | 5 | GET 200, estructura paginada (`data/links/meta`), array, filtro tipo, 404 id inexistente |
| `CategoriasApiTest.php` | 3 | GET 200, respuesta es array, Content-Type JSON |
| `ContactoApiTest.php` | 7 | POST 201 datos válidos; 422 sin nombre/email/mensaje; mensaje corto; payload vacío; estructura 201 |

**Resultado:** `15 passed (34 assertions)` — `Duration: 0.67s`

---

## Variables de Entorno Clave (`.env`)

```env
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_DATABASE=galdamez_erp

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=bryan.rm128@gmail.com
MAIL_PASSWORD=uifuxdrileqickwm
MAIL_FROM_ADDRESS="bryan.rm128@gmail.com"
MAIL_FROM_NAME="Galdámez S.A. de C.V."
MAIL_ADMIN_ADDRESS="bryan.rm128@gmail.com"

QUEUE_CONNECTION=database
FRONTEND_URL=http://localhost:5173
```

---

## Comandos de Desarrollo

```bash
# Servidor
php artisan serve

# Cola de trabajos (correos async)
php artisan queue:work

# Reset completo con seed
php artisan migrate:fresh --seed

# Enlace de storage
php artisan storage:link

# Tests (todos)
php artisan test

# Tests solo API
php artisan test --filter="Api"

# Ver rutas
php artisan route:list
```

---

## Próximas Fases (Pendientes)

- **Fase 7:** Integración completa React ↔ API ↔ MySQL ↔ Mail queue
- **Fase 8:** Producción — despliegue, variables reales, dominio, SSL
- **Fase 9 (TBD):** Algoritmo round-robin para asignación de agentes
