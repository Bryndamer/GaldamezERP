import { useState } from 'react';
import { Link, NavLink } from 'react-router-dom';
import { Sun, Moon, Menu, X } from 'lucide-react';
import { useTheme } from '../../context/ThemeContext';

const links = [
  { to: '/',            label: 'Inicio' },
  { to: '/propiedades', label: 'Propiedades' },
  { to: '/contacto',    label: 'Contacto' },
];

export default function Navbar() {
  const [open, setOpen] = useState(false);
  const { theme, toggleTheme } = useTheme();

  return (
    <header className="bg-white dark:bg-gray-950 border-b border-gray-200 dark:border-gray-800 sticky top-0 z-40 transition-colors duration-200">
      <nav className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

        {/* Logo */}
        <Link to="/" className="flex items-center gap-2.5 font-bold text-gray-900 dark:text-white">
          <div className="h-8 w-8 rounded-lg bg-amber-500 flex items-center justify-center flex-shrink-0">
            <svg className="h-4 w-4 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
              <path strokeLinecap="round" strokeLinejoin="round" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
          </div>
          <span className="text-sm tracking-tight">
            Galdámez <span className="font-light text-gray-500 dark:text-gray-400">S.A. de C.V.</span>
          </span>
        </Link>

        {/* Desktop links */}
        <div className="hidden md:flex items-center gap-7">
          {links.map(({ to, label }) => (
            <NavLink
              key={to}
              to={to}
              end={to === '/'}
              className={({ isActive }) =>
                `text-sm font-medium transition-colors ${
                  isActive
                    ? 'text-amber-600 dark:text-amber-400'
                    : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
                }`
              }
            >
              {label}
            </NavLink>
          ))}
        </div>

        {/* Actions */}
        <div className="flex items-center gap-2">
          <button
            onClick={toggleTheme}
            aria-label={theme === 'dark' ? 'Activar modo claro' : 'Activar modo oscuro'}
            data-testid="theme-toggle"
            className="p-2 rounded-lg text-gray-500 dark:text-gray-400
                       hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
          >
            {theme === 'dark'
              ? <Sun className="h-4 w-4" />
              : <Moon className="h-4 w-4" />
            }
          </button>

          <Link to="/contacto"
            className="hidden md:inline-flex items-center btn-primary text-sm !px-4 !py-2">
            Consultar propiedad
          </Link>

          <button
            className="md:hidden p-2 rounded-lg text-gray-600 dark:text-gray-400
                       hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            onClick={() => setOpen((v) => !v)}
            aria-label="Menú"
          >
            {open ? <X className="h-5 w-5" /> : <Menu className="h-5 w-5" />}
          </button>
        </div>
      </nav>

      {/* Mobile menu */}
      {open && (
        <div className="md:hidden border-t border-gray-100 dark:border-gray-800
                        bg-white dark:bg-gray-950 px-4 py-4 space-y-1">
          {links.map(({ to, label }) => (
            <NavLink
              key={to}
              to={to}
              end={to === '/'}
              onClick={() => setOpen(false)}
              className={({ isActive }) =>
                `block px-3 py-2.5 rounded-lg text-sm font-medium transition-colors ${
                  isActive
                    ? 'text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20'
                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900'
                }`
              }
            >
              {label}
            </NavLink>
          ))}
          <div className="pt-2">
            <Link to="/contacto" onClick={() => setOpen(false)}
              className="btn-primary w-full flex items-center justify-center text-sm">
              Consultar propiedad
            </Link>
          </div>
        </div>
      )}
    </header>
  );
}
