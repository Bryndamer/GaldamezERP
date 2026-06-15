import { Link } from 'react-router-dom';
import { ArrowRight } from 'lucide-react';
import { useInmuebles } from '../../hooks/useInmuebles';
import InmuebleCard from '../ui/InmuebleCard';

function SkeletonCard() {
  return (
    <div className="card animate-pulse">
      <div className="h-52 bg-gray-200 dark:bg-gray-800" />
      <div className="p-4 space-y-3">
        <div className="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/4" />
        <div className="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4" />
        <div className="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2" />
        <div className="h-6 bg-gray-200 dark:bg-gray-700 rounded w-1/3 mt-4" />
      </div>
    </div>
  );
}

export default function PropiedadesDestacadasSection() {
  const { inmuebles, loading, error } = useInmuebles({ per_page: 6 });

  return (
    <section className="py-20 bg-white dark:bg-gray-950 transition-colors duration-200">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div className="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12">
          <div>
            <span className="section-label mb-3 inline-block">Propiedades</span>
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">
              Propiedades destacadas
            </h2>
          </div>
          <Link
            to="/propiedades"
            className="inline-flex items-center gap-2 text-amber-600 dark:text-amber-400
                       hover:text-amber-700 dark:hover:text-amber-300 font-medium text-sm
                       transition-colors flex-shrink-0"
          >
            Ver todas
            <ArrowRight className="h-4 w-4" />
          </Link>
        </div>

        {error && (
          <p className="text-red-500 text-sm text-center py-8">{error}</p>
        )}

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {loading
            ? Array.from({ length: 6 }).map((_, i) => <SkeletonCard key={i} />)
            : inmuebles.slice(0, 6).map((inmueble) => (
                <InmuebleCard key={inmueble.id} inmueble={inmueble} />
              ))
          }
        </div>

        {!loading && inmuebles.length === 0 && !error && (
          <p className="text-center text-gray-400 dark:text-gray-500 py-12 text-sm">
            No hay propiedades disponibles en este momento.
          </p>
        )}

      </div>
    </section>
  );
}
