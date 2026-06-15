import { Phone, MessageCircle, Mail, MapPin, Clock } from 'lucide-react';

const CONTACTOS = [
  {
    icon: Phone,
    label: 'Teléfonos',
    lines: ['2301-1727', '2335-2608'],
    href: 'tel:+50323011727',
  },
  {
    icon: MessageCircle,
    label: 'WhatsApp',
    lines: ['+503 7799-1711'],
    href: 'https://wa.me/50377991711',
    external: true,
  },
  {
    icon: Mail,
    label: 'Correo',
    lines: ['info@bienesraicescentroamerica.com'],
    href: 'mailto:info@bienesraicescentroamerica.com',
  },
  {
    icon: MapPin,
    label: 'Dirección',
    lines: ['2a Calle Poniente, Barrio el Centro,', 'Chalatenango Sur, El Salvador.'],
    href: null,
  },
  {
    icon: Clock,
    label: 'Horario',
    lines: ['Lun – Vie: 8:00 am – 5:00 pm', 'Sábado: 8:00 am – 12:00 pm'],
    href: null,
  },
];

export default function MapaContactoSection() {
  return (
    <section className="py-20 bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div className="text-center mb-14">
          <span className="section-label mb-3 inline-block">Contacto</span>
          <h2 className="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">
            Visítenos o escríbanos
          </h2>
          <p className="mt-4 text-gray-500 dark:text-gray-400 max-w-lg mx-auto text-base leading-relaxed">
            Estamos en Chalatenango, El Salvador. Nuestro equipo está listo para atenderle
            de manera presencial o a distancia.
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

          {/* Info de contacto */}
          <div className="space-y-4">
            {CONTACTOS.map(({ icon: Icon, label, lines, href, external }) => {
              const inner = (
                <div className="card p-5 flex items-start gap-4 hover:shadow-md transition-shadow duration-200">
                  <span className="h-10 w-10 rounded-xl bg-amber-50 dark:bg-amber-900/20
                                   flex items-center justify-center flex-shrink-0">
                    <Icon className="h-5 w-5 text-amber-500" />
                  </span>
                  <div>
                    <p className="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">
                      {label}
                    </p>
                    {lines.map((line) => (
                      <p key={line} className="text-gray-800 dark:text-gray-200 text-sm leading-relaxed">
                        {line}
                      </p>
                    ))}
                  </div>
                </div>
              );

              if (href) {
                return (
                  <a
                    key={label}
                    href={href}
                    {...(external ? { target: '_blank', rel: 'noopener noreferrer' } : {})}
                    className="block group"
                  >
                    {inner}
                  </a>
                );
              }
              return <div key={label}>{inner}</div>;
            })}
          </div>

          {/* Mapa */}
          <div className="card overflow-hidden h-[460px] lg:sticky lg:top-24">
            <iframe
              title="Ubicación Galdámez S.A. de C.V."
              src="https://maps.google.com/maps?q=2a+Calle+Poniente+Chalatenango+El+Salvador&output=embed&z=15"
              className="w-full h-full border-0"
              loading="lazy"
              referrerPolicy="no-referrer-when-downgrade"
              allowFullScreen
            />
          </div>

        </div>
      </div>
    </section>
  );
}
