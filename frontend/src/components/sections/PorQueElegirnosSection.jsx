import { Award, CreditCard, UserCheck } from 'lucide-react';

const PILARES = [
  {
    icon: Award,
    title: 'Experiencia Garantizada',
    description:
      '25 años como empresa líder en bienes raíces, construcción y parcelaciones en el norte de El Salvador. Cada operación respaldada por procesos jurídicos y técnicos certificados.',
  },
  {
    icon: CreditCard,
    title: 'Facilidades de Financiamiento',
    description:
      'Te orientamos para obtener crédito hipotecario, apalancamiento y opciones de pago adaptadas a tu situación económica. Trabajamos con las principales instituciones financieras del país.',
  },
  {
    icon: UserCheck,
    title: 'Atención Personalizada',
    description:
      'Nuestro equipo acompaña cada etapa del proceso: desde la primera visita hasta la firma de escrituras. Atención directa en nuestra oficina de Chalatenango o en el predio de tu interés.',
  },
];

export default function PorQueElegirnosSection() {
  return (
    <section className="py-20 bg-white dark:bg-gray-950 transition-colors duration-200">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div className="text-center mb-14">
          <span className="section-label mb-3 inline-block">Por qué elegirnos</span>
          <h2 className="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">
            Más de 25 años a tu servicio
          </h2>
          <p className="mt-4 text-gray-500 dark:text-gray-400 max-w-xl mx-auto text-base leading-relaxed">
            En Galdámez S.A. de C.V. cada cliente recibe atención personalizada y procesos
            transparentes desde el primer contacto.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {PILARES.map(({ icon: Icon, title, description }) => (
            <div
              key={title}
              className="card p-8 flex flex-col gap-5 hover:shadow-md transition-shadow duration-200"
            >
              <div className="h-12 w-12 rounded-xl bg-amber-50 dark:bg-amber-900/20
                              flex items-center justify-center flex-shrink-0">
                <Icon className="h-6 w-6 text-amber-500" />
              </div>
              <div>
                <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-2">
                  {title}
                </h3>
                <p className="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                  {description}
                </p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
