# Proyecto ERP Galdámez — Plataforma de Bienes Raíces

**Director de Software:** Bryan Danilo Rauda Márquez
**Cliente:** Galdámez S.A. de C.V.
**Objetivo:** Reconstrucción total de la plataforma en 8 semanas, priorizando máxima seguridad y propiedad total del código.

---

## Regla de Enrutamiento (CRÍTICA)

Al iniciar cualquier sesión de trabajo, determina el contexto activo y carga los archivos correspondientes **antes de escribir una sola línea de código**:

| Contexto de trabajo | Archivos a leer obligatoriamente |
|---|---|
| Carpeta `backend/` | `backend/rules.md` → `backend/status.md` |
| Carpeta `frontend/` | `frontend/rules.md` → `frontend/status.md` |
| Raíz del proyecto | Este archivo + ambas carpetas según aplique |

> **Nunca asumas el estado del proyecto desde la memoria de sesión anterior. Siempre lee `status.md` del contexto activo para conocer el estado real y actualizado.**

---

## Stack Tecnológico

| Capa | Tecnología |
|---|---|
| Backend / Admin | Laravel 12.12.2 (PHP 8.2) + MySQL |
| Frontend público | React 19 + Vite 8 + Tailwind CSS v3 |
| Auth API | Laravel Sanctum 4 |
| Auth Admin | Sesiones nativas Laravel (no Sanctum) |
| Imágenes | PHP GD nativo → WebP |
| Cola de trabajos | Laravel Queue (driver: database) |
| Mail | Gmail SMTP (App Password) |
| Iconografía | lucide-react |

---

## Estructura del Repositorio

```
GaldamezERP/
├── backend/          # Laravel 12 — API REST + Panel Admin Blade
│   ├── rules.md      # Reglas estrictas del backend (leer antes de tocar código)
│   └── status.md     # Estado actualizado de avance del backend
├── frontend/         # React 19 + Vite 8 — Portal público
│   ├── rules.md      # Reglas estrictas del frontend (leer antes de tocar código)
│   └── status.md     # Estado actualizado de avance del frontend
└── CLAUDE.md         # Este archivo — enrutador global
```

---

## Principios Inquebrantables

1. **Seguridad primero** — CSRF en todos los formularios, rate limiting en todas las rutas públicas, nunca exponer datos sensibles en la API.
2. **Propiedad del código** — Cero dependencias externas donde PHP/Laravel nativo sea suficiente.
3. **Validación en Form Requests** — Nunca validar inline en controladores.
4. **RBAC nativo** — Gates y Middlewares propios; prohibido Spatie o paquetes de terceros para permisos.
5. **Tailwind nativo** — Prohibidos UI kits pesados (MUI, Bootstrap, Chakra). Excepción autorizada: `lucide-react` para iconografía.
6. **Estado documentado** — `backend/status.md` y `frontend/status.md` deben actualizarse al completar cada fase.
