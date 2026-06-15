import { Link } from 'react-router-dom';
import { Phone, MessageCircle, Mail, MapPin } from 'lucide-react';

const navLinks = [
  { to: '/',            label: 'Inicio' },
  { to: '/propiedades', label: 'Propiedades' },
  { to: '/contacto',    label: 'Contacto' },
];

export default function Footer() {
  return (
    <footer className="bg-slate-950 text-gray-400">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-10">

          {/* Marca */}
          <div className="md:col-span-2">
            <div className="flex items-center gap-2.5 mb-4">
              <div className="h-8 w-8 rounded-lg bg-amber-500 flex items-center justify-center flex-shrink-0">
                <svg className="h-4 w-4 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                  <path strokeLinecap="round" strokeLinejoin="round" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
              </div>
              <p className="font-bold text-white text-sm">Galdámez S.A. de C.V.</p>
            </div>
            <p className="text-sm leading-relaxed mb-4 max-w-xs">
              25 años de experiencia como empresa líder en bienes raíces, construcción
              y parcelaciones en el norte de El Salvador.
            </p>
            <div className="space-y-2 text-sm">
              <a href="tel:+50323011727"
                className="flex items-center gap-2 hover:text-white transition-colors">
                <Phone className="h-3.5 w-3.5 text-amber-500 flex-shrink-0" />
                2301-1727 / 2335-2608
              </a>
              <a href="https://wa.me/50377991711" target="_blank" rel="noopener noreferrer"
                className="flex items-center gap-2 hover:text-white transition-colors">
                <MessageCircle className="h-3.5 w-3.5 text-amber-500 flex-shrink-0" />
                +503 7799-1711
              </a>
              <a href="mailto:info@bienesraicescentroamerica.com"
                className="flex items-center gap-2 hover:text-white transition-colors">
                <Mail className="h-3.5 w-3.5 text-amber-500 flex-shrink-0" />
                info@bienesraicescentroamerica.com
              </a>
              <div className="flex items-start gap-2">
                <MapPin className="h-3.5 w-3.5 text-amber-500 flex-shrink-0 mt-0.5" />
                <span>2a Calle Poniente, Barrio el Centro, Chalatenango Sur, El Salvador.</span>
              </div>
            </div>
          </div>

          {/* Navegación */}
          <div>
            <p className="font-semibold text-white mb-4 text-xs uppercase tracking-widest">
              Navegación
            </p>
            <ul className="space-y-2 text-sm">
              {navLinks.map(({ to, label }) => (
                <li key={to}>
                  <Link to={to} className="hover:text-white transition-colors">{label}</Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Horario */}
          <div>
            <p className="font-semibold text-white mb-4 text-xs uppercase tracking-widest">
              Horario
            </p>
            <ul className="space-y-2 text-sm">
              <li>Lunes — Viernes</li>
              <li className="text-white font-medium">8:00 am — 5:00 pm</li>
              <li className="mt-3">Sábado</li>
              <li className="text-white font-medium">8:00 am — 12:00 pm</li>
            </ul>
          </div>

        </div>

        <div className="border-t border-slate-800 mt-10 pt-6 text-xs text-center space-y-1">
          <p>&copy; {new Date().getFullYear()} Galdámez S.A. de C.V. — Todos los derechos reservados.</p>
          <p>
            developed by{' '}
            <a
              href="https://portafolio-layout.vercel.app/Portafolioindex.html"
              target="_blank"
              rel="noopener noreferrer"
              className="text-amber-500 hover:text-amber-400 transition-colors"
            >
              Danilo Rauda
            </a>
            {' '}& Galdámez S.A. de C.V. | powered by WebExperience © 2026 todos los derechos reservados.
          </p>
        </div>
      </div>
    </footer>
  );
}
