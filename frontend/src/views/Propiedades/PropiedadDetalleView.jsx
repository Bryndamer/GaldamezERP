import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import { BedDouble, Bath, Maximize2, MapPin, Tag } from 'lucide-react';
import { getInmueble } from '../../services/inmuebles';
import ContactForm from '../../components/ui/ContactForm';

export default function PropiedadDetalleView() {
  const { id } = useParams();
  const [inmueble, setInmueble] = useState(null);
  const [loading, setLoading]   = useState(true);
  const [error, setError]       = useState(null);
  const [fotoIdx, setFotoIdx]   = useState(0);

  useEffect(() => {
    setLoading(true);
    setFotoIdx(0);
    getInmueble(id)
      .then((r) => setInmueble(r.data))
      .catch(() => setError('Propiedad no encontrada.'))
      .finally(() => setLoading(false));
  }, [id]);

  if (loading) return (
    <div className="max-w-6xl mx-auto px-4 py-16 animate-pulse space-y-4">
      <div className="h-5 bg-gray-100 rounded w-32" />
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div className="h-80 bg-gray-100 rounded-2xl" />
        <div className="space-y-4">
          <div className="h-6 bg-gray-100 rounded w-1/2" />
          <div className="h-10 bg-gray-100 rounded w-1/3" />
          <div className="h-40 bg-gray-100 rounded" />
        </div>
      </div>
    </div>
  );

  if (error || !inmueble) return (
    <div className="max-w-6xl mx-auto px-4 py-16 text-center">
      <p className="text-gray-500 mb-4">{error ?? 'Propiedad no disponible.'}</p>
      <Link to="/propiedades" className="btn-primary">Volver al listado</Link>
    </div>
  );

  const fotos = inmueble.fotos ?? [];

  return (
    <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

      <Link to="/propiedades" className="text-sm text-gray-500 hover:text-gray-700 mb-6 inline-flex items-center gap-1">
        ← Volver al listado
      </Link>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 mt-4">

        {/* Columna izquierda: Galería + Descripción */}
        <div className="lg:col-span-7 space-y-6">

          {/* Galería */}
          {fotos.length > 0 ? (
            <div>
              <div className="relative h-80 rounded-2xl overflow-hidden bg-gray-100">
                <img
                  src={fotos[fotoIdx]}
                  alt={inmueble.titulo}
                  className="h-full w-full object-cover"
                />
              </div>
              {fotos.length > 1 && (
                <div className="flex flex-wrap gap-2 mt-3">
                  {fotos.map((foto, i) => (
                    <button
                      key={i}
                      onClick={() => setFotoIdx(i)}
                      className={`flex-shrink-0 h-16 w-16 rounded-lg overflow-hidden border-2 transition-colors ${
                        fotoIdx === i ? 'border-blue-600' : 'border-transparent hover:border-gray-300'
                      }`}
                    >
                      <img src={foto} alt="" className="h-full w-full object-cover" />
                    </button>
                  ))}
                </div>
              )}
            </div>
          ) : (
            <div className="h-80 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-300">
              <svg className="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1}
                  d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
              </svg>
            </div>
          )}

          {/* Descripción */}
          <div className="card p-6">
            <h2 className="font-semibold text-gray-900 mb-3">Descripción</h2>
            <p className="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
              {inmueble.descripcion}
            </p>
          </div>

        </div>

        {/* Columna derecha: Detalles + Formulario */}
        <div className="lg:col-span-5 space-y-6">

          {/* Detalles de la propiedad */}
          <div className="card p-6">
            {inmueble.categoria?.name && (
              <p className="text-xs text-blue-600 font-semibold uppercase tracking-wider mb-2">
                {inmueble.categoria.name}
              </p>
            )}
            <h1 className="text-2xl font-bold text-gray-900 mb-1">{inmueble.titulo}</h1>

            {inmueble.direccion && (
              <p className="flex items-center gap-1 text-sm text-gray-500 mb-4">
                <MapPin className="h-4 w-4 flex-shrink-0" />
                {inmueble.direccion}
              </p>
            )}

            <p className="text-3xl font-bold text-blue-600 mb-5">
              ${inmueble.precio?.toLocaleString('en-US')}
            </p>

            <div className="grid grid-cols-3 gap-3 mb-2">
              {inmueble.habitaciones > 0 && (
                <div className="bg-gray-50 rounded-xl p-3 text-center">
                  <BedDouble className="h-5 w-5 mx-auto text-gray-400 mb-1" />
                  <p className="font-bold text-gray-900 text-sm">{inmueble.habitaciones}</p>
                  <p className="text-xs text-gray-500">Hab.</p>
                </div>
              )}
              {inmueble.banos > 0 && (
                <div className="bg-gray-50 rounded-xl p-3 text-center">
                  <Bath className="h-5 w-5 mx-auto text-gray-400 mb-1" />
                  <p className="font-bold text-gray-900 text-sm">{inmueble.banos}</p>
                  <p className="text-xs text-gray-500">Baños</p>
                </div>
              )}
              {inmueble.metraje && (
                <div className="bg-gray-50 rounded-xl p-3 text-center">
                  <Maximize2 className="h-5 w-5 mx-auto text-gray-400 mb-1" />
                  <p className="font-bold text-gray-900 text-sm">{inmueble.metraje}</p>
                  <p className="text-xs text-gray-500">m²</p>
                </div>
              )}
            </div>

            {inmueble.tipo && (
              <div className="flex items-center gap-1 mt-3 text-xs text-gray-500">
                <Tag className="h-3 w-3" />
                <span className="capitalize">{inmueble.tipo}</span>
              </div>
            )}
          </div>

          {/* Formulario de contacto */}
          <div className="card p-6">
            <h2 className="font-semibold text-gray-900 mb-4">Consultar esta propiedad</h2>
            <ContactForm inmuebleId={inmueble.id} compact />
          </div>

        </div>
      </div>
    </div>
  );
}
