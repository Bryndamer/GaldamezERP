# GaldamezERP — Plataforma de Bienes Raíces

ERP inmobiliario desarrollado a medida para **Galdámez S.A. de C.V.** Permite gestionar inmuebles, categorías, usuarios con roles, mensajes de contacto y envío de correos con plantillas editables desde el panel administrativo.

---

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend / Panel Admin | Laravel 12 (PHP 8.2) + MySQL 8 |
| Frontend público | React 19 + Vite 8 + Tailwind CSS v3 |
| Autenticación admin | Sesiones nativas Laravel |
| Autenticación API | Laravel Sanctum 4 |
| Imágenes | PHP GD nativo → WebP |
| Cola de trabajos | Laravel Queue (driver: database) |
| Correo | Gmail SMTP (App Password) |
| Iconografía | lucide-react |
| Contenedores | Docker + Docker Compose |

---

## Estructura del repositorio

```
GaldamezERP/
├── docker-compose.yml    # Orquesta mysql + backend + frontend
├── .gitignore
├── CLAUDE.md             # Guía de contexto para Claude Code (IA)
├── README.md             # Este archivo
├── backend/              # Laravel 12 — API REST + Panel Admin Blade
│   ├── docker/           # Configuración Docker (nginx, supervisor, entrypoint)
│   ├── Dockerfile
│   ├── app/              # Modelos, Controladores, Mail, Middleware, Commands
│   ├── database/         # Migraciones, Seeders, Factories
│   ├── routes/           # web.php (admin) + api.php (público)
│   ├── resources/        # Vistas Blade + plantillas de email
│   ├── .env.example      # Template de variables de entorno
│   └── README.md
└── frontend/             # React 19 + Vite 8 — Portal público
    ├── Dockerfile
    ├── src/              # Componentes, vistas, hooks, servicios
    ├── .env.example      # Template de variables de entorno
    └── README.md
```

---

## Requisitos

### Con Docker (recomendado)

| Herramienta | Versión mínima |
|---|---|
| Docker Desktop | 4.x |
| Docker Compose | v2 |

### Sin Docker (local)

| Herramienta | Versión mínima |
|---|---|
| PHP | 8.2 |
| Composer | 2.x |
| Node.js | 20+ |
| npm | 10+ |
| MySQL | 8.0 |

---

## Instalación con Docker

### 1. Clonar el repositorio

```bash
git clone https://github.com/<usuario>/GaldamezERP.git
cd GaldamezERP
```

### 2. Configurar el APP_KEY del backend

```bash
# Copiar el .env con tus credenciales de correo
cp backend/.env.example backend/.env
```

Editar `backend/.env` y completar las variables de Gmail (ver sección Variables de entorno).

### 3. Levantar todos los contenedores

```bash
docker compose up --build
```

La primera vez el build tarda ~3 minutos. Una vez listo, los servicios estarán disponibles en:

| Servicio | URL |
|---|---|
| Panel Admin | http://localhost:8000/login |
| Portal público React | http://localhost:5173 |
| API REST | http://localhost:8000/api |
| MySQL (cliente externo) | localhost:3309 |

### 4. Primera vez: poblar con datos de prueba

```bash
docker compose exec backend php artisan demodata
```

---

## Instalación local (sin Docker)

### 1. Configurar el backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Editar `backend/.env` con tus credenciales de MySQL y Gmail.

```bash
php artisan migrate
php artisan demodata        # Poblar con datos de prueba
php artisan storage:link    # Enlazar almacenamiento público
```

### 2. Configurar el frontend

```bash
cd ../frontend
npm install
cp .env.example .env
```

### 3. Levantar los servidores

```bash
# Terminal 1 — Backend
cd backend && php artisan serve

# Terminal 2 — Frontend
cd frontend && npm run dev
```

---

## Credenciales de demo

Generadas con `php artisan demodata`:

| Rol | Email | Contraseña |
|---|---|---|
| Admin | admin@galdamez.com | password123 |
| Agente | agente@galdamez.com | password123 |

---

## Comandos útiles

```bash
# ─── Con Docker ────────────────────────────────────────────────────────────
docker compose up --build               # Primera vez o tras cambios de código
docker compose up                       # Siguientes veces
docker compose down                     # Detener contenedores
docker compose down -v                  # Detener y borrar volúmenes (BD incluida)
docker compose logs -f backend          # Ver logs del backend en tiempo real
docker compose exec backend php artisan demodata          # Datos de prueba
docker compose exec backend php artisan email:test        # Diagnóstico SMTP
docker compose exec backend php artisan migrate           # Migraciones
docker compose exec backend php artisan test              # Tests PHPUnit

# ─── Local (sin Docker) ────────────────────────────────────────────────────
php artisan demodata                    # Insertar datos de prueba
php artisan demodata --fresh            # migrate:fresh + datos de prueba
php artisan email:test                  # Diagnóstico SMTP completo
php artisan email:test --to=tu@email.com
php artisan queue:work                  # Procesar cola de jobs
php artisan test                        # PHPUnit (15 tests)
cd frontend && npm run test             # Vitest (38 tests)
```

---

## Variables de entorno

Cada capa tiene su propio `.env.example` documentado:

- `backend/.env.example` — MySQL, Gmail SMTP, CORS, Queue
- `frontend/.env.example` — URL de la API

**Nunca subas archivos `.env` al repositorio.** Ya están en `.gitignore`.

> Para Docker, las variables de entorno críticas de la BD ya están definidas en `docker-compose.yml`. Solo necesitas configurar las variables de correo en `backend/.env`.

---

## API REST (endpoints públicos)

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/api/inmuebles` | Listado paginado con filtros (precio, tipo, categoría, habitaciones) |
| GET | `/api/inmuebles/{id}` | Detalle de inmueble |
| GET | `/api/categorias` | Lista de categorías |
| POST | `/api/mensajes` | Formulario de contacto (rate limit: 5/hora por IP) |

---

## Arquitectura Docker

```
docker-compose.yml
  ├── mysql       → MySQL 8.0      puerto 3309 (host) : 3306 (interno)
  ├── backend     → PHP-FPM+Nginx  puerto 8000
  └── frontend    → Node 20/Vite   puerto 5173
```

Los tres servicios comparten la red interna `galdamez_network`. El backend se conecta a MySQL usando el hostname `mysql` (nombre del servicio). El navegador accede a ambos servicios a través de los puertos expuestos en `localhost`.

---

## Licencia

Código propietario — Galdámez S.A. de C.V. Todos los derechos reservados.
