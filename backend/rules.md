# Backend — Reglas Estrictas

**Rol activo:** Experto en Laravel 12 y PHP 8.2
**Leer siempre junto a:** `backend/status.md`

---

## 1. Validación — Solo Form Requests

- **Prohibido** validar con `$request->validate()` inline en controladores.
- Toda validación vive en `app/Http/Requests/`.
- Subcarpetas por módulo: `Inmueble/`, `Category/`, `User/`, `Api/`, `PlantillaCorreo/`.
- Los Form Requests para la API deben sobreescribir `failedValidation()` para retornar JSON 422.

```php
// CORRECTO
public function store(StoreInmuebleRequest $request): RedirectResponse { ... }

// PROHIBIDO
public function store(Request $request): RedirectResponse {
    $request->validate([...]);
}
```

---

## 2. RBAC — Gates y Middlewares Nativos

- **Prohibido** instalar Spatie Permission u otro paquete de permisos de terceros.
- Roles: `admin` y `agente` (enum en tabla `users`).
- **Middleware:** `app/Http/Middleware/CheckRole.php` — alias `role`.
- **Gate:** `manage-inmueble` en `AppServiceProvider`.
  - Admin: acceso total. Agente: solo sus inmuebles (`user_id === Auth::id()`).
- Helpers en User: `isAdmin()`, `isAgente()`.

---

## 3. Imágenes — PHP GD Nativo

- **Prohibido** instalar Intervention Image, Imagick u otros paquetes de imagen.
- Toda conversión en `app/Services/ImageService.php` (GD nativo).
- Formatos de entrada: `image/jpeg`, `image/png`, `image/webp`.
- Salida siempre en **WebP** — calidad 85, ancho máximo 1920px.
- Almacenamiento: disco `public` → `storage/app/public/inmuebles/{uuid}.webp`.
- GD habilitado en `php.ini` (`extension=gd`).

---

## 4. API Resources — Exposición de Datos

- **Prohibido** retornar modelos Eloquent directamente como JSON.
- Toda respuesta de API pasa por `app/Http/Resources/`.
- `InmuebleResource` — `fotos` como URLs absolutas via `Storage::disk('public')->url($path)`.
- `CategoryResource` — expone `id`, `name`, `slug`.

---

## 5. Autenticación — Dos Capas Separadas

| Capa | Mecanismo | Guardia |
|---|---|---|
| Panel Admin (Blade) | Sesión nativa Laravel | `web` |
| API pública (React) | Sin autenticación (pública) | — |
| API privada (futuro) | Sanctum tokens | `sanctum` |

- CORS: `config/cors.php` permite `localhost:5173` y el `FRONTEND_URL` de producción.

---

## 6. Controladores y Organización

- Controladores Blade: `app/Http/Controllers/` (raíz).
- Controladores API: `app/Http/Controllers/Api/` (subnamespace `Api\`).
- Paginación: `->paginate(10)` en admin Blade, `->paginate(12)` en API pública.

---

## 7. Seguridad — Reglas Mínimas

- `@csrf` en todos los formularios Blade.
- `@method('DELETE')` / `@method('PUT')` en formularios que lo requieran.
- Rate limiting: `login` → 5/min por IP+email. `contacto` → 5/hora por IP.
- Nunca exponer stack traces ni datos sensibles en respuestas API.

---

## 8. Cola de Trabajos (Queue)

- `QUEUE_CONNECTION=database` en `.env` de producción.
- Mailables de notificación implementan `ShouldQueue` + `SerializesModels`.
- `Mail::queue()` en controladores de API; `Mail::send()` solo para reenvíos manuales en admin.
- El fallo del mail **no debe devolver error 500** al cliente — siempre en try/catch.
- En desarrollo: `php artisan queue:work`.

---

## 9. Correo — Plantillas Editables

- Las plantillas de correo viven en la tabla `plantillas_correo` (no hardcodeadas en Blade).
- Acceso via `PlantillaCorreo::porIdentificador(string $id)` — lanza 404 si no existe.
- Identificadores activos: `contacto_admin`, `contacto_cliente`.
- Token de sustitución: `:nombre` — reemplazado con `str_replace()` en el Mailable.
- Edición en `/admin/plantillas`.

---

## 10. Testing — PHPUnit

**Config:** `phpunit.xml` — SQLite `:memory:`, `MAIL_MAILER=array`, `QUEUE_CONNECTION=sync`

**Reglas:**
- Tests de API en `tests/Feature/Api/` — namespace `Tests\Feature\Api`.
- Usar `RefreshDatabase` en todo test que toque base de datos.
- Llamar `Mail::fake()` en `setUp()` de tests que disparan emails.
- Los tests de API usan `$this->getJson()` / `$this->postJson()` (no `$this->get()`).
- Usar `assertJsonValidationErrors(['campo'])` para verificar errores 422.

```php
// CORRECTO
public function test_contacto_exitoso_retorna_201(): void
{
    $this->postJson('/api/v1/contacto', $datos)->assertStatus(201);
}
```

**Comandos:**
```bash
php artisan test                          # todos los tests
php artisan test --filter="Api"           # solo tests de API
php artisan test tests/Feature/Api/       # carpeta específica
php artisan test --coverage              # con cobertura (requiere Xdebug)
```

**Estado actual:** 15 tests — 3 archivos — todos passing.
