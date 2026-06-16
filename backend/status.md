# Backend — Estado de Avance

**Última actualización:** 2026-06-15 (correcciones Docker en PC nueva)
**Versión Laravel:** 12.12.2 (PHP 8.2)
**Base de datos:** MySQL — `GaldamezERP` (puerto 3309 en local con Docker)
**Servidor local:** `php artisan serve` → http://localhost:8000
**Docker:** `docker compose up --build` → http://localhost:8000
**Tests:** ✓ 15/15 PHPUnit — `php artisan test`

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
- `Inmueble` — fotos cast array, `booted()` deleting event limpia Storage, **HasFactory** ✓
- `Category` — fillable: name/slug, hasMany(Inmueble), **HasFactory** ✓
- `Mensaje` — fillable completo, belongsTo(Inmueble) nullable, **HasFactory** ✓

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
- `Api/MensajeApiController` — store con try/catch mail, envío directo `Mail::send()`
- `Api/ContactoRequest` — `failedValidation()` retorna JSON 422

---

### Fase 5 — Panel Admin Completo ✅

**Controladores admin:**
- `CategoryController` — destroy protege si tiene inmuebles
- `UserController` — destroy verifica no sea el usuario actual
- `MensajeController` — markRead (PATCH toggle `leido`), destroy, **reenviarCorreos** (Mail::send sync, null-check de plantillas antes del try/catch, log con trace en error)

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
| `app/Mail/ConfirmacionContacto.php` | Mailable → cliente |
| `resources/views/emails/confirmacion_contacto.blade.php` | Email HTML al cliente |
| `app/Http/Requests/PlantillaCorreo/UpdatePlantillaCorreoRequest.php` | Validación |
| `app/Http/Controllers/PlantillaCorreoController.php` | index / edit / update |
| `resources/views/admin/plantillas/index.blade.php` | Lista de plantillas |
| `resources/views/admin/plantillas/edit.blade.php` | Formulario de edición con token `:nombre` |

**Archivos modificados:**

| Archivo | Cambio |
|---|---|
| `app/Mail/NuevoMensajeContacto.php` | Inyecta `PlantillaCorreo` para asunto y cuerpo. Eliminado `ShouldQueue` y `Queueable` — envío directo |
| `app/Mail/ConfirmacionContacto.php` | Eliminado `ShouldQueue` y `Queueable` — envío directo |
| `resources/views/emails/nuevo_mensaje.blade.php` | Usa variables de plantilla |
| `app/Http/Controllers/Api/ContactoController.php` | Envío con `Mail::send()` directo (antes `->queue()`), log con trace en error |
| `app/Http/Controllers/Api/MensajeApiController.php` | Igual que ContactoController |
| `app/Http/Controllers/MensajeController.php` | Null-check de plantillas antes del try/catch, log mejorado con trace |
| `routes/web.php` | Rutas `admin.plantillas` + `mensajes.reenviar` |
| `resources/views/layouts/admin.blade.php` | Link "Plantillas de Correo" en sidebar |

**Flujo:**
- `POST /api/v1/contacto` → guarda Mensaje → envía 2 correos directo (admin + cliente)
- `POST /admin/mensajes/{id}/reenviar` → `Mail::send()` sincrónico, muestra error SMTP si falla
- `GET /admin/plantillas` → lista editable de las 2 plantillas
- Token `:nombre` disponible en asunto y saludo

---

### Suite de Tests PHPUnit ✅ (2026-05-29)

**Config:** `phpunit.xml` — SQLite `:memory:`, `MAIL_MAILER=array`, `QUEUE_CONNECTION=sync`

**Tests en `tests/Feature/Api/`:**

| Archivo | Tests | Qué valida |
|---|---|---|
| `InmueblesApiTest.php` | 5 | GET 200, estructura paginada (`data/links/meta`), array, filtro tipo, 404 id inexistente |
| `CategoriasApiTest.php` | 3 | GET 200, respuesta es array, Content-Type JSON |
| `ContactoApiTest.php` | 7 | POST 201 datos válidos; 422 sin nombre/email/mensaje; mensaje corto; payload vacío; estructura 201 |

**Resultado:** `15 passed (34 assertions)` — `Duration: 0.67s`

---

### Configuración BD → Docker MySQL ✅ (2026-06-15)

- `DB_PORT` cambiado de `3306` a `3309` (MySQL en contenedor Docker mapeado al host)
- `DB_DATABASE` cambiado de `galdamez_erp` a `GaldamezERP`
- `DB_USERNAME=root`, `DB_PASSWORD=rootpassword`
- En Docker Compose, el backend usa `DB_HOST=mysql` (hostname del servicio) con `DB_PORT=3306` (puerto interno)

---

### Factories y Trait HasFactory ✅ (2026-06-15)

Añadido `HasFactory` a los tres modelos que lo requerían:

| Modelo | Archivo |
|---|---|
| `Category` | `app/Models/Category.php` |
| `Inmueble` | `app/Models/Inmueble.php` |
| `Mensaje` | `app/Models/Mensaje.php` |

**Factories creadas:**

| Factory | Archivo | Datos generados |
|---|---|---|
| `CategoryFactory` | `database/factories/CategoryFactory.php` | Nombres "Casa 01"…"Casa XX", slug via `Str::slug` |
| `InmuebleFactory` | `database/factories/InmuebleFactory.php` | Colonias y municipios salvadoreños, tipo pesa habitaciones/baños, estado ponderado (disponible ×3) |
| `MensajeFactory` | `database/factories/MensajeFactory.php` | Teléfono formato `7###-####`, tipo fijo 'contacto' |

---

### Comando `php artisan demodata` ✅ (2026-06-15)

**Archivo:** `app/Console/Commands/DemoDataCommand.php`

**Firma:** `demodata {--fresh : Ejecutar migrate:fresh antes de insertar datos}`

**Lo que hace:**
1. Trunca tablas con `SET FOREIGN_KEY_CHECKS=0` en orden correcto
2. Crea 5 categorías hardcodeadas (Casa, Apartamento, Terreno, Local Comercial, Bodega)
3. Crea 1 admin (`admin@galdamez.com/password123`) + 1 agente fijo + 2 agentes Faker
4. Crea 30 inmuebles usando `recycle()` (distribuye sobre usuarios/categorías existentes)
5. Crea 20 mensajes usando `recycle()` (distribuye sobre usuarios existentes)
6. Llama `PlantillaCorreoSeeder` para upsert de plantillas de correo
7. Muestra tabla resumen en consola

```bash
php artisan demodata              # Trunca y repobla
php artisan demodata --fresh      # migrate:fresh + repobla
docker compose exec backend php artisan demodata
```

---

### Comando `php artisan email:test` ✅ (2026-06-15)

**Archivo:** `app/Console/Commands/EmailTestCommand.php`

**Firma:** `email:test {--to=} {--queue}`

**Diagnóstico en 3 pasos:**
1. Muestra configuración SMTP activa (contraseña con últimos 4 chars visibles)
2. Verifica conectividad TCP con `fsockopen($host, $port, $errno, $errstr, 5)`
3. Envía correo de prueba y captura errores específicos

**Errores identificados:**
- `535 Username and Password not accepted` → App Password inválida o 2FA no activado
- `Connection refused / timed out` → Firewall bloqueando puerto 587
- `SSL/TLS` → Configuración de cifrado incorrecta
- `5.7.0 relay` o `534` → `MAIL_FROM_ADDRESS` no coincide con `MAIL_USERNAME`

**Advertencias automáticas:**
- Si `MAIL_USERNAME` ≠ `MAIL_FROM_ADDRESS`
- Si `MAIL_USERNAME` no es `@gmail.com`

```bash
php artisan email:test
php artisan email:test --to=tu@email.com
php artisan email:test --queue
docker compose exec backend php artisan email:test --to=tu@email.com
```

---

### Corrección: Envío de Correos Directo (sin cola) ✅ (2026-06-15)

**Problema:** Los Mailables tenían `implements ShouldQueue` / `use Queueable`, lo que requería `queue:work` en ejecución. Sin worker, los correos se encolaban silenciosamente y nunca se enviaban.

**Solución aplicada:**

| Archivo | Cambio |
|---|---|
| `app/Mail/NuevoMensajeContacto.php` | Eliminado `implements ShouldQueue`, `use Queueable`. Solo `use SerializesModels` |
| `app/Mail/ConfirmacionContacto.php` | Igual |
| `Api/ContactoController.php` | `->queue(new ...)` → `->send(new ...)` |
| `Api/MensajeApiController.php` | Igual |
| `MensajeController.php` | Null-check de plantillas antes del try/catch + log con `trace` |

Los correos ahora se envían de forma síncrona. Errores SMTP son inmediatamente visibles en el log y en la UI del panel admin.

---

### Git — Monorepo en GitHub ✅ (2026-06-15)

- Eliminados `.git` embebidos de `backend/` y `frontend/` (ambos tenían historial previo)
- Creado `.gitignore` en raíz (`.claude/`, `.env`, `vendor/`, `node_modules/`, `dist/`, `*.log`, archivos OS)
- Primer commit: `79e9407 feat: initial commit — GaldamezERP monorepo (Laravel 12 + React 19)` (175 archivos)
- Rama principal: `main`

---

### Docker — Configuración de Contenedores ✅ (2026-06-15)

**Archivos creados:**

| Archivo | Descripción |
|---|---|
| `backend/Dockerfile` | Multi-stage: Stage 1 node:20-alpine (Vite build) → Stage 2 PHP 8.2-FPM Alpine + Nginx + Supervisor + Composer |
| `backend/docker/nginx.conf` | Puerto 8000, proxy FastCGI a 127.0.0.1:9000, `client_max_body_size 20M` |
| `backend/docker/supervisord.conf` | Corre `php-fpm` y `nginx` simultáneamente, logs a stdout/stderr |
| `backend/docker/entrypoint.sh` | Espera MySQL (30 reintentos × 3s) → genera APP_KEY si falta → migrate → storage:link → supervisord |
| `frontend/Dockerfile` | Node 20 Alpine, `yarn install`, `vite dev --host 0.0.0.0 --port 5173` |
| `docker-compose.yml` (raíz) | Orquesta mysql + backend + frontend en red `galdamez_network` |

**Puertos expuestos al host:**

| Servicio | Puerto host | Puerto contenedor |
|---|---|---|
| mysql | 3309 | 3306 |
| backend | 8000 | 8000 |
| frontend | 5173 | 5173 |

**Volúmenes persistentes:**
- `galdamez_mysql_data` — datos de MySQL
- `galdamez_backend_storage` — imágenes subidas (`storage/app/public`)

**Networking:** backend → mysql usa hostname `mysql:3306` (interno). El navegador usa `localhost:8000` y `localhost:5173` (expuestos al host). `VITE_API_URL=http://localhost:8000/api` porque las peticiones las hace el navegador desde el host.

```bash
docker compose up --build                              # Primera vez
docker compose up                                      # Inicios siguientes
docker compose exec backend php artisan demodata       # Datos de prueba
docker compose exec backend php artisan email:test     # Diagnóstico SMTP
docker compose down -v                                 # Destruir todo (incluye BD)
```

---

### Correcciones Docker al clonar en PC nueva ✅ (2026-06-15)

Serie de fixes aplicados al configurar el proyecto en una PC nueva sin PHP/Node local.

#### Fix 1 — Dockerfile: multi-stage build

**Problema:** `apk add nodejs npm` fallaba con exit code 3 en `php:8.2-fpm-alpine` (Alpine 3.21). El paquete `npm` en Alpine 3.21 causa conflicto de resolución con `nodejs`.

**Solución:** Convertido a multi-stage build. Node.js ya no se instala vía apk.

| Stage | Base | Responsabilidad |
|---|---|---|
| `node_builder` | `node:20-alpine` | `yarn install` + `yarn build` → assets Vite en `/build/public/build/` |
| `stage-1` (final) | `php:8.2-fpm-alpine` | PHP runtime, Composer, Nginx, Supervisor. Copia assets del stage anterior |

```dockerfile
COPY --from=node_builder /build/public/build /var/www/public/build
```

También corregido bug en línea original: `COPY vite.config.js resources/ ./vite.config.js ./` → separado en dos instrucciones correctas.

---

#### Fix 2 — Dockerfiles: npm → Yarn

**Problema:** `npm ci` requiere `package-lock.json`. El repositorio no tiene lockfile de npm.

**Solución:** Ambos Dockerfiles cambiados a Yarn (incluido en `node:20-alpine` sin instalación adicional).

| Archivo | Cambio |
|---|---|
| `backend/Dockerfile` (stage node_builder) | `COPY package.json yarn.lock* ./` + `RUN yarn install` + `RUN yarn build` |
| `frontend/Dockerfile` | `COPY package.json yarn.lock* ./` + `RUN yarn install` + `CMD ["yarn", "dev", ...]` |

> El glob `yarn.lock*` hace el lockfile opcional: si no existe, yarn lo genera; si existe, lo usa.

---

#### Fix 3 — composer.json: faker movido a `require`

**Problema:** `fakerphp/faker` estaba en `require-dev`. El Dockerfile instala con `--no-dev`, así que faker no estaba disponible en el contenedor. En Laravel 12, `fake()` solo se define si `\Faker\Generator` existe → `Call to undefined function Database\Factories\fake()` al correr `demodata`.

**Solución:** Movido a `require` (dependencia de producción).

```json
"require": {
    "fakerphp/faker": "^1.23",
    ...
}
```

Afecta a: `UserFactory`, `CategoryFactory`, `InmuebleFactory`, `MensajeFactory`.

---

#### Fix 4 — Dockerfile: `composer install` → `composer update`

**Problema:** Al mover faker manualmente en `composer.json` sin actualizar `composer.lock`, `composer install` rechaza instalar con exit code 4 (`lock file not up to date`).

**Solución:** Cambiado a `composer update` en el Dockerfile. Resuelve el desajuste durante el build y no requiere `composer.lock` sincronizado en el host.

> Para volver a `composer install` en el futuro: extraer el lockfile actualizado del contenedor con `docker compose cp backend:/var/www/composer.lock ./backend/composer.lock`.

---

#### Fix 5 — entrypoint.sh: MySQL wait loop

**Problema:** El loop usaba `php artisan db:show` para verificar disponibilidad de MySQL. Este comando arranca la aplicación Laravel completa, que intenta inicializar `CACHE_STORE=database` y `SESSION_DRIVER=database`. Las tablas `cache` y `sessions` no existen aún (las migraciones corren después del loop), por lo que el comando siempre fallaba con exit ≠ 0, aunque MySQL estuviera activo y la BD existiera.

**Solución:** Cambiado a check TCP directo con PHP puro, sin arrancar Laravel:

```sh
# Antes (fallaba aunque MySQL estuviera activo):
until php artisan db:show > /dev/null 2>&1; do

# Después (solo verifica TCP 3306, sin necesitar tablas):
until php -r "exit(@fsockopen('${DB_HOST}', ${DB_PORT}, \$e, \$s, 2) ? 0 : 1);" > /dev/null 2>&1; do
```

---

#### Archivos `.env` creados

| Archivo | Descripción |
|---|---|
| `backend/.env` | Creado desde `.env.example`. DB: `127.0.0.1:3309/GaldamezERP/root`. Mail: `noreply@bienesraicescentroamerica.com` con App Password configurada. |
| `frontend/.env` | `VITE_API_URL=http://localhost:8000/api` |

> En Docker, las variables `DB_*` del `backend/.env` son sobreescritas por el bloque `environment:` del `docker-compose.yml` (`DB_HOST=mysql`, `DB_PORT=3306`). El `.env` sirve para conexiones locales (fuera de Docker, desde el host).

---

## Variables de Entorno Clave (`.env` local)

```env
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3309
DB_DATABASE=GaldamezERP
DB_USERNAME=root
DB_PASSWORD=rootpassword

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=tu-cuenta@gmail.com
MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx   # App Password 16 chars sin espacios
MAIL_FROM_ADDRESS="tu-cuenta@gmail.com"   # Debe coincidir exactamente con MAIL_USERNAME
MAIL_FROM_NAME="Galdámez S.A. de C.V."
MAIL_ADMIN_ADDRESS="admin@ejemplo.com"

QUEUE_CONNECTION=database
FRONTEND_URL=http://localhost:5173
```

> En Docker, las variables `DB_*` se inyectan desde `docker-compose.yml`. Solo configura las variables de correo en `backend/.env`.

---

## Comandos de Desarrollo

```bash
# ─── Con Docker ───────────────────────────────────────────────────────
docker compose up --build
docker compose exec backend php artisan demodata
docker compose exec backend php artisan demodata --fresh
docker compose exec backend php artisan email:test --to=tu@email.com
docker compose exec backend php artisan test
docker compose logs -f backend

# ─── Sin Docker (local) ───────────────────────────────────────────────
php artisan serve
php artisan demodata
php artisan demodata --fresh
php artisan email:test
php artisan email:test --to=tu@email.com
php artisan queue:work         # solo si se usa cola (no requerido para correos)
php artisan migrate:fresh
php artisan storage:link
php artisan test
php artisan route:list
```

---

## Próximas Fases (Pendientes)

- **Fase 7:** Integración completa React ↔ API ↔ MySQL ↔ Mail (testing E2E)
- **Fase 8:** Producción — despliegue, variables reales, dominio, SSL, Dockerfile multi-stage para frontend
- **Fase 9 (TBD):** Algoritmo round-robin para asignación de agentes
