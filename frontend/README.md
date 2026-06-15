# GaldamezERP — Frontend (React 19)

Portal público de la plataforma de bienes raíces de Galdámez S.A. de C.V. Consume la API REST del backend Laravel.

## Stack

- **React** 19.2
- **Vite** 8
- **Tailwind CSS** v3 — con dark mode (`darkMode: 'class'`)
- **React Router DOM** v7
- **Axios** — cliente HTTP centralizado
- **lucide-react** — iconografía

---

## Setup con Docker (recomendado)

Desde la raíz del monorepo:

```bash
docker compose up --build
```

El contenedor corre `vite dev` con `--host 0.0.0.0` para ser accesible desde el host. Los directorios `src/` y `public/` se montan como volúmenes, por lo que los cambios en el código se reflejan con hot reload sin reconstruir la imagen.

Portal disponible en: **http://localhost:5173**

---

## Setup local (sin Docker)

```bash
# 1. Instalar dependencias
npm install

# 2. Copiar y configurar variables de entorno
cp .env.example .env

# 3. Levantar servidor de desarrollo
npm run dev
```

El portal estará disponible en: **http://localhost:5173**

> El backend Laravel debe estar corriendo en http://localhost:8000 antes de levantar el frontend.

---

## Variables de entorno

| Variable | Descripción | Valor por defecto |
|---|---|---|
| `VITE_API_URL` | URL base de la API Laravel sin trailing slash | `http://localhost:8000/api` |

> En Docker, esta variable se inyecta desde `docker-compose.yml`. En local, se configura en `frontend/.env`.

---

## Rutas del portal público

| Ruta | Vista | Descripción |
|---|---|---|
| `/` | `HomeView` | Hero + propiedades destacadas + contacto |
| `/propiedades` | `PropiedadesView` | Listado filtrable con paginación |
| `/propiedades/:id` | `PropiedadDetalleView` | Detalle + galería de fotos + formulario contacto |
| `/contacto` | `ContactoView` | Formulario de contacto + mapa |
| `*` | `NotFoundView` | Página 404 |

---

## Estructura de carpetas

```
src/
├── services/          # Funciones Axios puras (sin estado)
│   ├── api.js         # Instancia base de Axios con baseURL desde VITE_API_URL
│   ├── inmuebles.js   # getInmuebles(), getInmueble(), getCategorias()
│   └── mensajes.js    # enviarMensaje()
├── hooks/             # Lógica de estado reutilizable (sin JSX)
│   └── useInmuebles.js
├── context/           # Contextos globales
│   └── ThemeContext.jsx  # Dark/light mode con localStorage
├── components/
│   ├── layout/        # Navbar, Footer
│   ├── sections/      # Secciones autónomas de página
│   └── ui/            # Componentes presentacionales (InmuebleCard, ContactForm)
└── views/             # Páginas — orquestan secciones
    ├── Home/
    ├── Propiedades/
    ├── Contacto/
    └── NotFound/
```

---

## Dark mode

El tema se persiste en `localStorage`. El toggle está en la **Navbar**. Se usa la estrategia `darkMode: 'class'` de Tailwind: agregar la clase `dark` al `<html>` activa el modo oscuro en todos los componentes.

Color de acento: **amber**.

---

## Secciones del Home

| Sección | Descripción |
|---|---|
| `HeroSection` | Selector de tipo de propiedad + estadísticas (25 años) |
| `PorQueElegirnosSection` | 3 tarjetas de propuesta de valor |
| `QuienesSomosSection` | Descripción de la empresa |
| `PropiedadesDestacadasSection` | Grid de 6 inmuebles desde la API |
| `MapaContactoSection` | Formulario de contacto + iframe Google Maps |

---

## Formulario de contacto

- Valida campos antes de enviar (nombre, email, mensaje obligatorios).
- Muestra errores de validación devueltos por la API (`422`).
- Muestra aviso de rate limit si se exceden 5 envíos por hora (`429`).
- Deshabilita el botón durante el envío para evitar doble submit.

---

## Docker — detalles del contenedor

```
frontend/
└── Dockerfile    # Node 20 Alpine · instala deps · corre vite dev --host 0.0.0.0
```

**Hot reload en Docker:** los volúmenes montados en `docker-compose.yml` sincronizan `src/` y `public/` del host al contenedor en tiempo real. Los cambios en código fuente se reflejan instantáneamente sin reconstruir la imagen.

```yaml
# En docker-compose.yml
volumes:
  - ./frontend/src:/app/src       # ← hot reload
  - ./frontend/public:/app/public
  - /app/node_modules             # ← usa los del build, no del host
```

**Vite config para Docker** (`vite.config.js`):
```js
server: {
  host: '0.0.0.0',   // escucha en todas las interfaces
  port: 5173,
}
```

---

## Tests

```bash
npm run test        # Modo watch (desarrollo)
npm run test:run    # Una sola ejecución (CI)

# Con Docker:
docker compose exec frontend npm run test:run
```

- **38 tests** con Vitest + Testing Library.
- Cada componente y vista tiene su suite de tests.
- Los tests usan `MemoryRouter` y `ThemeProvider` para aislar contexto.

---

## Build de producción

```bash
npm run build       # Genera dist/
npm run preview     # Preview del build en local
```

> Para producción con Docker, el `Dockerfile` puede modificarse para usar un build multi-stage: `node:20-alpine` para compilar y `nginx:alpine` para servir el `dist/` resultante.

---

## Reglas de desarrollo

1. **Solo Axios** para peticiones HTTP — prohibido `fetch()` nativo.
2. **Sin URLs hardcodeadas** — siempre usar `import.meta.env.VITE_API_URL`.
3. **Tailwind nativo** — prohibidos UI kits pesados (MUI, Bootstrap, Chakra).
4. **Estado inmutable** — usar spread operator, nunca mutar directamente.
5. **lucide-react** para iconografía — no importar librerías de íconos adicionales.
