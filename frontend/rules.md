# Frontend — Reglas Estrictas

**Rol activo:** Experto en React 19, Vite 8 y Tailwind CSS v3
**Stack:** React 19 + Vite 8 + Tailwind CSS v3 + Axios + React Router DOM v7 + lucide-react
**API base:** `http://localhost:8000/api` (desarrollo) — `VITE_API_URL` en `.env`

---

## 1. Arquitectura Modular Estricta

```
src/
├── context/
│   └── ThemeContext.jsx        # ThemeProvider + useTheme — dark/light mode
├── services/                  # Capa de acceso a API (solo Axios, sin lógica de UI)
│   ├── api.js                 # Instancia Axios centralizada
│   ├── inmuebles.js           # getInmuebles(), getInmueble(), getCategorias()
│   └── mensajes.js            # enviarMensaje()
├── hooks/                     # Custom hooks (estado + efectos, sin JSX)
│   └── useInmuebles.js
├── components/
│   ├── Layout.jsx             # Wrapper global: ThemeProvider + Navbar + children + Footer
│   ├── layout/
│   │   ├── Navbar.jsx         # Toggle dark mode (data-testid="theme-toggle"), sticky top
│   │   └── Footer.jsx         # Datos reales empresa, crédito Danilo Rauda
│   ├── sections/              # Secciones de página — independientes y testeables
│   │   ├── HeroSection.jsx
│   │   ├── PorQueElegirnosSection.jsx
│   │   ├── QuienesSomosSection.jsx
│   │   ├── PropiedadesDestacadasSection.jsx
│   │   └── MapaContactoSection.jsx
│   └── ui/                    # Componentes de presentación reutilizables
│       ├── InmuebleCard.jsx
│       └── ContactForm.jsx
├── views/                     # Páginas — orquestan secciones y hooks
│   ├── Home/HomeView.jsx
│   ├── Propiedades/PropiedadesView.jsx
│   ├── Propiedades/PropiedadDetalleView.jsx
│   └── Contacto/ContactoView.jsx
├── __tests__/                 # Tests Vitest — uno por componente/hook/context
│   ├── ThemeContext.test.jsx
│   ├── Navbar.test.jsx
│   ├── HeroSection.test.jsx
│   └── ...
├── test/
│   └── setup.js               # @testing-library/jest-dom + mock matchMedia
└── App.jsx                    # Solo rutas
```

**Reglas de organización:**
- `services/` — funciones puras Axios. Sin hooks, sin estado.
- `hooks/` — lógica de estado y efectos. Sin JSX.
- `components/sections/` — secciones de página autónomas (props mínimas, se autoabastecen).
- `components/ui/` — presentación pura, reciben props.
- `views/` — orquestan secciones y hooks. No tienen lógica de UI propia.

---

## 2. Consumo de API — Solo vía Axios Centralizado

- **Prohibido** usar `fetch()` nativo en componentes.
- **Prohibido** hardcodear URLs de API en componentes o vistas.
- Toda llamada HTTP pasa por `src/services/api.js`.

```js
// CORRECTO
import api from './api';
export const getInmuebles = (params) => api.get('/inmuebles', { params });

// PROHIBIDO
const res = await fetch('http://localhost:8000/api/inmuebles');
```

---

## 3. Estado Inmutable

```jsx
// CORRECTO
setForm((f) => ({ ...f, [e.target.name]: e.target.value }));

// PROHIBIDO
form.nombre = e.target.value;
setForm(form);
```

---

## 4. Diseño — Tailwind CSS Nativo + Paleta Amber

- **Color de acento:** amber (`amber-500` / `amber-600` / `amber-400` en dark)
- **Prohibido** instalar UI kits (MUI, Bootstrap, Chakra, Ant Design). Excepción: `lucide-react`.
- Todo diseño usa clases utilitarias Tailwind CSS v3.

**Clases de componente disponibles** (definidas en `src/index.css` vía `@layer components`):

| Clase | Descripción |
|---|---|
| `.btn-primary` | Botón amber con hover, foco, estado disabled |
| `.btn-outline` | Botón con borde, soporte `dark:` |
| `.input-field` | Input/select/textarea con estados `dark:` |
| `.card` | Contenedor `bg-white dark:bg-gray-900`, border, shadow |
| `.section-label` | Etiqueta pequeña amber uppercase tracking-widest |
| `.section-title` | Título de sección h2 con `dark:` |
| `.section-subtitle` | Párrafo secundario con `dark:` |

El diseño debe ser **100% responsivo.** Breakpoints: `sm` (640px), `md` (768px), `lg` (1024px).

---

## 5. Dark Mode — Obligatorio en Todo Componente Nuevo

- Estrategia: `darkMode: 'class'` en `tailwind.config.js`.
- El `ThemeProvider` en `Layout.jsx` gestiona la clase `dark` en `<html>`.
- **Todo componente nuevo debe incluir clases `dark:`** para: fondos, textos, bordes, badges, placeholders.
- Paleta estándar:

| Elemento | Light | Dark |
|---|---|---|
| Fondo body | `bg-white` | `dark:bg-gray-950` |
| Fondo cards | `bg-white` | `dark:bg-gray-900` |
| Bordes | `border-gray-200` | `dark:border-gray-800` |
| Texto principal | `text-gray-900` | `dark:text-gray-100` |
| Texto secundario | `text-gray-500` | `dark:text-gray-400` |

---

## 6. Enrutamiento — React Router DOM v7

- Enrutador en `src/main.jsx` (`<BrowserRouter>`).
- Rutas en `src/App.jsx`.
- Navegación con `<Link>` o `<NavLink>`. **Nunca `<a href>` para rutas internas.**
- `useNavigate()` para redirecciones programáticas.

**Rutas actuales:**
| Path | Componente |
|---|---|
| `/` | `HomeView` |
| `/propiedades` | `PropiedadesView` |
| `/propiedades/:id` | `PropiedadDetalleView` |
| `/contacto` | `ContactoView` |
| `*` | 404 inline |

---

## 7. Manejo de Errores en Formularios

- Error 422: `err.response.data.errors` → mapear al estado `errors` por campo.
- Error 429: mensaje genérico en `errors.general`.
- Otros errores: mensaje genérico en `errors.general`.
- Nunca mostrar stack traces al usuario.

---

## 8. Variables de Entorno

```env
VITE_API_URL=http://localhost:8000/api
```

---

## 9. Testing — Vitest + Testing Library

**Framework:** Vitest v4 + jsdom + @testing-library/react

**Reglas:**
- Un archivo de test por componente/hook/context en `src/__tests__/`.
- Nombres: `NombreComponente.test.jsx`.
- Usar `vi.mock()` para aislar dependencias externas (hooks, servicios).
- Componentes que usan `<Link>` o `useNavigate` necesitan `<MemoryRouter>` en el test.
- Componentes que usan `useTheme` necesitan `<ThemeProvider>` en el test.
- El setup en `src/test/setup.js` provee el mock de `window.matchMedia`.

**Comandos:**
```bash
npm run test        # modo watch (desarrollo)
npm run test:run    # una ejecución (CI/pre-commit)
```

**Estado actual:** 38 tests — 7 archivos — todos passing.
