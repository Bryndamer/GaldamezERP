# Despliegue — hosting.com (Enhance · LiteSpeed · PHP 8.3)

## Cómo funciona el pipeline

Workflow: `.github/workflows/deploy.yml`. Se ejecuta en cada push a `main` (o manualmente).

1. Instala dependencias PHP de producción (`composer install --no-dev`) en `backend/`.
2. Compila el frontend React con Vite en `backend/public/spa/` (base `/spa/`).
3. Sube `backend/` al servidor por **rsync + SSH** (`--delete`, filtros en `.rsync-exclude`).
4. Por SSH ejecuta: `storage:link`, `migrate --force`, `db:seed --class=ProductionSeeder --force`, `config/route/view/event:cache` y `queue:restart`.

El document root del dominio apunta a `backend/public` (`DEPLOY_PATH/public`).
Laravel sirve la SPA con la ruta catch-all `spa` (`routes/web.php`); `/admin`, `/agente`,
`/login` y `/api` siguen siendo del backend. El panel admin usa Tailwind por CDN (sin build npm).

## Secrets del repositorio (Settings → Secrets and variables → Actions)

| Secret | Descripción |
|---|---|
| `SSH_HOST` | Host SSH del servidor |
| `SSH_PORT` | Puerto SSH |
| `SSH_USER` | Usuario SSH |
| `SSH_PRIVATE_KEY` | Clave privada cuya pública está autorizada en el servidor |
| `DEPLOY_PATH` | Ruta absoluta del proyecto Laravel en el servidor (sin `/` final) |
| `VITE_API_URL` | URL base de la API para el build, p. ej. `https://constructoraylotificadoragaldamez.com/api` |

## Qué NO sube el deploy

- `.env` → se crea y mantiene **a mano** en el servidor (`DEPLOY_PATH/.env`).
- `storage/` (logs, sesiones, caché, imágenes subidas) y el enlace `public/storage`.
- `tests/`, `docker/`, `Dockerfile`, `phpunit.xml`, archivos `.md` de documentación.

Los archivos excluidos no se borran en el servidor aunque se use `--delete`.

## Primer despliegue

1. Configurar los secrets y el document root.
2. Ejecutar el workflow una vez (sube el código; avisa que falta `.env`).
3. Crear `DEPLOY_PATH/.env` (`APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY`, BD MySQL,
   `QUEUE_CONNECTION=database`, Gmail SMTP, `FRONTEND_URL`) y generar la clave con `php artisan key:generate`.
4. Volver a ejecutar el workflow para migrar y cachear.

## Cron (cola de correos)

No hay `queue:work` permanente. En el panel Enhance, crear un cron **cada minuto**:

```
* * * * * cd DEPLOY_PATH && php artisan schedule:run >> /dev/null 2>&1
```

El scheduler (`routes/console.php`) ejecuta `queue:work --stop-when-empty` cada minuto.

## Deploy manual

GitHub → **Actions** → *Deploy to hosting.com* → **Run workflow** (rama `main`).
Útil tras crear `.env` o para redesplegar sin nuevos commits.

## Operación por SSH

Desde el Mac de desarrollo hay un alias `galdamez` en `~/.ssh/config` (el proyecto vive en `~/app`):

```bash
ssh galdamez 'cd ~/app && php artisan about'
ssh galdamez 'cd ~/app && tail -n 50 storage/logs/laravel.log'
ssh -t galdamez 'cd ~/app && php artisan make:admin'
ssh galdamez 'cd ~/app && php artisan email:test --to=correo@ejemplo.com'
ssh galdamez 'cd ~/app && php artisan queue:work --stop-when-empty'
```

`make:admin` es interactivo (pide la contraseña oculta): usar siempre `ssh -t`.

## Primer arranque en producción

1. Deploy en verde (GitHub Actions).
2. Crear el administrador: `ssh -t galdamez 'cd ~/app && php artisan make:admin'`.
3. Verificar SMTP: `php artisan email:test --to=<correo>`.
4. Probar el login del panel y el formulario de contacto del sitio.

`ProductionSeeder` (categorías base + plantillas de correo) corre en **cada deploy** y es idempotente;
no crea usuarios. `php artisan demodata` **no** debe usarse en producción (borra todos los datos).
