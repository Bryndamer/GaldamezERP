# Frontend — Estado de Avance

**Última actualización:** 2026-05-29
**Stack instalado:** React 19.2 + Vite 8 + Tailwind CSS v3 + React Router DOM v7 + Axios + lucide-react
**Servidor local:** `npm run dev` → http://localhost:5173
**Build verificado:** ✓ sin errores
**Tests:** ✓ 38/38 Vitest — `npm run test:run`

---

## Fases Completadas

### Fase 1 — Inicialización del Proyecto ✅

- Proyecto creado con `npm create vite@latest frontend -- --template react`
- **Dependencias instaladas:**
  - `tailwindcss@3`, `postcss`, `autoprefixer`
  - `axios` — comunicación con API
  - `react-router-dom@7` — enrutamiento SPA
  - `lucide-react` — iconografía SVG
- `tailwind.config.js` con `content` escaneando `./src/**/*.{js,jsx}`
- `frontend/.env` — `VITE_API_URL=http://localhost:8000/api`

---

### Fase 2 — UI/UX y Consumo de API ✅

#### Estructura de carpetas (antes del rediseño)

```
src/
├── services/
│   ├── api.js              → Instancia Axios centralizada (baseURL=VITE_API_URL)
│   ├── inmuebles.js        → getInmuebles(params), getInmueble(id), getCategorias()
│   └── mensajes.js         → enviarMensaje(data) → POST /api/mensajes
├── hooks/
│   └── useInmuebles.js     → estado: inmuebles[], meta, loading, error, page, filters
├── components/
│   ├── Layout.jsx
│   ├── layout/
│   │   ├── Navbar.jsx
│   │   └── Footer.jsx
│   └── ui/
│       ├── InmuebleCard.jsx
│       └── ContactForm.jsx
├── views/
│   ├── Home/HomeView.jsx
│   ├── Propiedades/PropiedadesView.jsx
│   ├── Propiedades/PropiedadDetalleView.jsx
│   └── Contacto/ContactoView.jsx
└── App.jsx
```

#### Rutas activas

| Path | Vista | Descripción |
|---|---|---|
| `/` | `HomeView` | Orquesta las 5 secciones del rediseño |
| `/propiedades` | `PropiedadesView` | Listado filtrable + paginación |
| `/propiedades/:id` | `PropiedadDetalleView` | Galería + detalles + ContactForm inline |
| `/contacto` | `ContactoView` | Formulario + mapa de contacto |
| `*` | inline | "Página no encontrada" |

---

### Fase 3 — Rediseño Premium con Dark Mode ✅ (2026-05-29)

**Objetivo:** rediseño completo del portal público con modo oscuro nativo, paleta amber, contenido real de Galdámez y componentes de sección independientes.

#### Cambios globales

| Archivo | Cambio |
|---|---|
| `tailwind.config.js` | `darkMode: 'class'` activado |
| `src/index.css` | Paleta amber, base dark, utilidades: `.btn-primary`, `.btn-outline`, `.input-field`, `.card`, `.section-label`, `.section-title` |
| `src/context/ThemeContext.jsx` | **NUEVO** — provee `{ theme, toggleTheme }` vía Context; persiste en localStorage; respeta `prefers-color-scheme` |
| `src/components/Layout.jsx` | Envuelto en `<ThemeProvider>`, base `dark:bg-gray-950` |

#### Componentes layout actualizados

| Archivo | Cambio |
|---|---|
| `src/components/layout/Navbar.jsx` | Toggle sol/luna (`data-testid="theme-toggle"`), logo amber, clases `dark:` completas |
| `src/components/layout/Footer.jsx` | Datos reales empresa: teléfonos, WhatsApp, email, dirección, horario; crédito "developed by Danilo Rauda" |

#### Secciones nuevas (`src/components/sections/`)

| Componente | Descripción |
|---|---|
| `HeroSection.jsx` | Hero full-bleed, fondo `slate-900`, gradiente `from-slate-950`, selector de tipo + botón buscar, stats (25 años / Zona Norte / 100%) |
| `PorQueElegirnosSection.jsx` | 3 tarjetas: Experiencia Garantizada (`Award`), Facilidades de Financiamiento (`CreditCard`), Atención Personalizada (`UserCheck`) |
| `QuienesSomosSection.jsx` | 2 columnas: placeholder foto + info empresa (dirección, teléfonos, WhatsApp, email) |
| `PropiedadesDestacadasSection.jsx` | Reutiliza `useInmuebles` + `InmuebleCard`, máx 6, skeleton loading, link "Ver todas" |
| `MapaContactoSection.jsx` | Cards de contacto + iframe Google Maps (sin API key) de `2a+Calle+Poniente+Chalatenango` |

#### Componentes UI actualizados

| Archivo | Cambio |
|---|---|
| `src/components/ui/InmuebleCard.jsx` | Clases `dark:` en badges, fondo imagen placeholder, texto, hover amber en lugar de azul |
| `src/components/ui/ContactForm.jsx` | Clases `dark:` completas; reemplazó `.input-field` por constantes inline para manejo granular de error; labels amber |

#### Vistas actualizadas

| Archivo | Cambio |
|---|---|
| `src/views/Home/HomeView.jsx` | Reescrito como orquestador puro de 5 secciones — sin lógica propia |
| `src/views/Contacto/ContactoView.jsx` | Sección de encabezado + `<ContactForm>` + `<MapaContactoSection>` |

---

### Fase 4 — Suite de Tests Vitest ✅ (2026-05-29)

**Paquetes instalados (devDependencies):**
- `vitest@4`
- `jsdom@29`
- `@testing-library/react@16`
- `@testing-library/user-event@14`
- `@testing-library/jest-dom@6`

**Configuración:**
- `vite.config.js` — bloque `test: { environment: 'jsdom', globals: true, setupFiles: './src/test/setup.js' }`
- `src/test/setup.js` — importa `@testing-library/jest-dom` + mock de `window.matchMedia`
- `package.json` — scripts `test` (watch) y `test:run` (CI)

**Tests creados en `src/__tests__/`:**

| Archivo | Tests | Qué valida |
|---|---|---|
| `ThemeContext.test.jsx` | 5 | Toggle añade/quita clase `dark`, localStorage, error fuera de Provider |
| `Navbar.test.jsx` | 5 | Logo, 3 links de nav, botón tema visible |
| `HeroSection.test.jsx` | 6 | Titular, acento amber, 25 años, placeholder foto, botón buscar, select |
| `PorQueElegirnosSection.test.jsx` | 6 | Label, título, 3 tarjetas por nombre, count exacto de h3 |
| `QuienesSomosSection.test.jsx` | 6 | Titular, 25 años, Chalatenango, email, WhatsApp, placeholder foto |
| `MapaContactoSection.test.jsx` | 6 | Título, teléfonos, WhatsApp, email, iframe mapa, horario |
| `PropiedadesDestacadasSection.test.jsx` | 4 | Título sección, "Ver todas", tarjeta mockeada, precio |

**Resultado:** `38 passed (38)` — `Duration: 1.58s`

---

## Variables de Entorno

```env
# frontend/.env
VITE_API_URL=http://localhost:8000/api
```

---

## Comandos de Desarrollo

```bash
npm run dev        # servidor de desarrollo (puerto 5173)
npm run build      # build de producción
npm run preview    # previsualizar build localmente
npm run test       # tests en modo watch
npm run test:run   # tests una sola vez (CI)
```

---

## Próximas Fases (Pendientes)

- **Fase 5:** Integración completa — React ↔ Laravel API ↔ MySQL ↔ Mail queue
- **Fase 6:** Producción — variables reales, dominio, SSL, CDN de imágenes
