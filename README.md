# GaldamezERP — Plataforma de Bienes Raíces

ERP inmobiliario desarrollado a medida para **Galdámez S.A. de C.V.** Permite gestionar inmuebles, categorías, usuarios con roles, mensajes de contacto y envío de correos con plantillas editables desde el panel administrativo.

---

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend / Panel Admin | Laravel 12 (PHP 8.2) + MySQL |
| Frontend público | React 19 + Vite 8 + Tailwind CSS v3 |
| Autenticación admin | Sesiones nativas Laravel |
| Autenticación API | Laravel Sanctum 4 |
| Imágenes | PHP GD nativo → WebP |
| Cola de trabajos | Laravel Queue (driver: database) |
| Correo | Gmail SMTP (App Password) |
| Iconografía | lucide-react |

---

## Estructura del repositorio

```
GaldamezERP/
├── backend/          # Laravel 12 — API REST + Panel Admin Blade
│   ├── app/          # Modelos, Controladores, Mail, Middleware, Commands
│   ├── database/     # Migraciones, Seeders, Factories
│   ├── routes/       # web.php (admin) + api.php (público)
│   ├── resources/    # Vistas Blade + plantillas de email
│   ├── .env.example  # Template de variables de entorno
│   └── README.md     # Documentación del backend
├── frontend/         # React 19 + Vite 8 — Portal público
│   ├── src/          # Componentes, vistas, hooks, servicios
│   ├── .env.example  # Template de variables de entorno
│   └── README.md     # Documentación del frontend
└── CLAUDE.md         # Guía de contexto para Claude Code (IA)
```

---

## Requisitos

| Herramienta | Versión mínima |
|---|---|
| PHP | 8.2 |
| Composer | 2.x |
| Node.js | 20+ |
| npm | 10+ |
| MySQL | 8.0 (o Docker) |

---

## Instalación rápida

### 1. Clonar el repositorio

```bash
git clone https://github.com/<usuario>/GaldamezERP.git
cd GaldamezERP
```

### 2. Configurar el backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Editar `backend/.env` con tus credenciales de MySQL y Gmail (ver sección Variables de entorno).

```bash
php artisan migrate
php artisan demodata        # Poblar con datos de prueba
php artisan storage:link    # Enlazar almacenamiento público
```

### 3. Configurar el frontend

```bash
cd ../frontend
npm install
cp .env.example .env
```

Editar `frontend/.env` si el backend corre en un puerto diferente al 8000.

### 4. Levantar los servidores

```bash
# Terminal 1 — Backend
cd backend && php artisan serve

# Terminal 2 — Frontend
cd frontend && npm run dev
```

| Servicio | URL |
|---|---|
| Panel Admin | http://localhost:8000/login |
| Portal público | http://localhost:5173 |
| API REST | http://localhost:8000/api |

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
# Insertar datos de demostración en todas las tablas
php artisan demodata

# Insertar datos + migrar desde cero
php artisan demodata --fresh

# Probar la conexión SMTP y enviar correo de diagnóstico
php artisan email:test
php artisan email:test --to=tu@email.com

# Procesar la cola de trabajos (si se usa modo queue)
php artisan queue:work

# Correr tests
cd backend && php artisan test
cd frontend && npm run test
```

---

## Variables de entorno

Cada capa tiene su propio `.env.example` documentado:

- `backend/.env.example` — MySQL, Gmail SMTP, Sanctum, CORS
- `frontend/.env.example` — URL de la API

**Nunca subas archivos `.env` al repositorio.** Ya están en `.gitignore`.

---

## API REST (endpoints públicos)

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/api/inmuebles` | Listado paginado con filtros |
| GET | `/api/inmuebles/{id}` | Detalle de inmueble |
| GET | `/api/categorias` | Lista de categorías |
| POST | `/api/mensajes` | Formulario de contacto (rate limit: 5/hora) |

---

## Licencia

Código propietario — Galdámez S.A. de C.V. Todos los derechos reservados.
