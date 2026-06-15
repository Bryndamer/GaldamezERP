import { Phone, MessageCircle, Mail, MapPin } from 'lucide-react';

export default function QuienesSomosSection() {
  return (
    <section className="py-20 bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

          {/* Foto placeholder */}
          <div className="relative rounded-2xl overflow-hidden bg-slate-200 dark:bg-slate-800
                          min-h-[340px] flex items-center justify-center order-2 lg:order-1">
            <div className="border border-dashed border-slate-400 dark:border-slate-600
                            rounded-xl px-8 py-6 max-w-xs text-center">
              <p className="text-slate-500 dark:text-slate-400 text-xs font-mono tracking-wider leading-relaxed">
                [ AQUÍ SE INCLUIRÁ FOTOGRAFÍA DE LA OFICINA<br />O DEL EQUIPO DE TRABAJO ]
              </p>
            </div>
          </div>

          {/* Texto */}
          <div className="order-1 lg:order-2">
            <span className="section-label mb-4 inline-block">Quiénes somos</span>
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
              Empresa líder en bienes raíces<br />
              <span className="text-amber-500">desde 1999</span>
            </h2>

            <div className="space-y-4 text-gray-600 dark:text-gray-300 text-base leading-relaxed">
              <p>
                Con más de 25 años de trayectoria, <strong className="text-gray-900 dark:text-white">Galdámez S.A. de C.V.</strong>{' '}
                se ha consolidado como la empresa de referencia en bienes raíces, construcción
                y parcelaciones en la zona norte de El Salvador.
              </p>
              <p>
                Nuestro equipo acompaña cada transacción con rigor jurídico y técnico,
                garantizando seguridad tanto para compradores como para vendedores. Cada
                proceso es transparente, documentado y respaldado por escrituras notariales.
              </p>
            </div>

            {/* Datos de contacto */}
            <div className="mt-8 space-y-3 text-sm">
              <a href="tel:+50323011727"
                className="flex items-center gap-3 text-gray-600 dark:text-gray-300
                           hover:text-amber-600 dark:hover:text-amber-400 transition-colors group">
                <span className="h-9 w-9 rounded-lg bg-amber-50 dark:bg-amber-900/20
                                 flex items-center justify-center flex-shrink-0
                                 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/40 transition-colors">
                  <Phone className="h-4 w-4 text-amber-500" />
                </span>
                2301-1727 / 2335-2608
              </a>

              <a href="https://wa.me/50377991711" target="_blank" rel="noopener noreferrer"
                className="flex items-center gap-3 text-gray-600 dark:text-gray-300
                           hover:text-amber-600 dark:hover:text-amber-400 transition-colors group">
                <span className="h-9 w-9 rounded-lg bg-amber-50 dark:bg-amber-900/20
                                 flex items-center justify-center flex-shrink-0
                                 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/40 transition-colors">
                  <MessageCircle className="h-4 w-4 text-amber-500" />
                </span>
                +503 7799-1711 (WhatsApp)
              </a>

              <a href="mailto:info@bienesraicescentroamerica.com"
                className="flex items-center gap-3 text-gray-600 dark:text-gray-300
                           hover:text-amber-600 dark:hover:text-amber-400 transition-colors group">
                <span className="h-9 w-9 rounded-lg bg-amber-50 dark:bg-amber-900/20
                                 flex items-center justify-center flex-shrink-0
                                 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/40 transition-colors">
                  <Mail className="h-4 w-4 text-amber-500" />
                </span>
                info@bienesraicescentroamerica.com
              </a>

              <div className="flex items-start gap-3 text-gray-600 dark:text-gray-300">
                <span className="h-9 w-9 rounded-lg bg-amber-50 dark:bg-amber-900/20
                                 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <MapPin className="h-4 w-4 text-amber-500" />
                </span>
                <span>
                  2a Calle Poniente, Barrio el Centro,<br />
                  Chalatenango Sur, El Salvador.
                </span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
  );
}
