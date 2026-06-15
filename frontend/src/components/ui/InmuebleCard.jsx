import { Link } from 'react-router-dom';

const estadoBadge = {
  disponible: 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
  reservado:  'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
  vendido:    'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
};

export default function InmuebleCard({ inmueble }) {
  const { id, titulo, precio, tipo, habitaciones, banos, metraje, estado, fotos, categoria } = inmueble;
  const portada = fotos?.[0] ?? null;

  return (
    <Link to={`/propiedades/${id}`} className="card group flex flex-col hover:shadow-md transition-shadow">

      {/* Imagen */}
      <div className="relative h-52 bg-gray-100 dark:bg-gray-800 flex-shrink-0">
        {portada ? (
          <img
            src={portada}
            alt={titulo}
            className="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
          />
        ) : (
          <div className="h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
            <svg className="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5}
                d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
          </div>
        )}
        {estado && (
          <span className={`absolute top-2 right-2 text-xs font-semibold px-2 py-0.5 rounded-full capitalize ${estadoBadge[estado]}`}>
            {estado}
          </span>
        )}
      </div>

      {/* Contenido */}
      <div className="p-4 flex flex-col flex-1">
        {categoria?.name && (
          <p className="text-xs text-amber-600 dark:text-amber-500 font-medium mb-1 capitalize">{categoria.name}</p>
        )}
        <h3 className="font-semibold text-gray-900 dark:text-white line-clamp-2 mb-1 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
          {titulo}
        </h3>

        <p className="text-xl font-bold text-gray-900 dark:text-white mt-auto pt-3">
          ${precio?.toLocaleString('en-US')}
        </p>

        <div className="flex items-center gap-3 mt-2 text-sm text-gray-500 dark:text-gray-400">
          {habitaciones > 0 && (
            <span className="flex items-center gap-1">
              <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                <path strokeLinecap="round" strokeLinejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
              </svg>
              {habitaciones} hab
            </span>
          )}
          {banos > 0 && (
            <span>{banos} baño{banos !== 1 ? 's' : ''}</span>
          )}
          {metraje && (
            <span>{metraje} m²</span>
          )}
        </div>
      </div>
    </Link>
  );
}
