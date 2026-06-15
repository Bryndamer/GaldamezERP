import { useSearchParams } from 'react-router-dom';
import ContactForm from '../../components/ui/ContactForm';
import MapaContactoSection from '../../components/sections/MapaContactoSection';

export default function ContactoView() {
  const [searchParams] = useSearchParams();
  const inmuebleId = searchParams.get('inmueble_id');

  return (
    <>
      {/* Encabezado + formulario */}
      <section className="py-16 bg-white dark:bg-gray-950 transition-colors duration-200">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="max-w-2xl mx-auto">
            <span className="section-label mb-3 inline-block">Contacto</span>
            <h1 className="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">
              Hablemos de tu propiedad
            </h1>
            <p className="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
              Completa el formulario y un agente de Galdámez S.A. de C.V. se pondrá en contacto
              contigo a la brevedad posible.
            </p>

            <div className="card p-6 sm:p-8">
              <ContactForm inmuebleId={inmuebleId ? parseInt(inmuebleId, 10) : null} />
            </div>
          </div>
        </div>
      </section>

      {/* Mapa + datos de contacto */}
      <MapaContactoSection />
    </>
  );
}
