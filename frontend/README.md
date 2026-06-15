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

## Setup local

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

| Variable | Descripción | Default |
|---|---|---|
| `VITE_API_URL` | URL base de la API Laravel sin trailing slash | `http://localhost:8000/api` |

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
│   ├── api.js         # Instancia base de Axios con baseURL
│   ├── inmuebles.js   # getInmuebles(), getInmueble(), getCategorias()
│   └── mensajes.js    # enviarMensaje()
├── hooks/             # Lógica de estado reutilizable (sin JSX)
│   └── useInmuebles.js
├── context/           # Contextos globales
│   └── ThemeContext.jsx  # Dark/light mode con localStorage
├── components/
│   ├── layout/        # Navbar, Footer
│   ├── sections/      # Secciones de página (Hero, Mapa, etc.)
│   └── ui/            # Componentes presentacionales (InmuebleCard, etc.)
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
| `MapaContactoSection` | Formulario + iframe Google Maps |

---

## Formulario de contacto

- Valida campos antes de enviar (nombre, email, mensaje obligatorios).
- Muestra errores de validación devueltos por la API (`422`).
- Muestra aviso de rate limit si se exceden 5 envíos por hora (`429`).
- Deshabilita el botón durante el envío para evitar doble submit.

---

## Tests

```bash
npm run test        # Modo watch (desarrollo)
npm run test:run    # Una sola ejecución (CI)
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

---

## Reglas de desarrollo

1. **Solo Axios** para peticiones HTTP — prohibido `fetch()` nativo.
2. **Sin URLs hardcodeadas** — siempre usar `import.meta.env.VITE_API_URL`.
3. **Tailwind nativo** — prohibidos UI kits pesados (MUI, Bootstrap, Chakra).
4. **Estado inmutable** — usar spread operator, nunca mutar directamente.
5. **lucide-react** para iconografía — no importar librerías de íconos adicionales.
