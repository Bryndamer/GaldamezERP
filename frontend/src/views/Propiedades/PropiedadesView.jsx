import { useState, useEffect } from 'react';
import { useSearchParams } from 'react-router-dom';
import InmuebleCard from '../../components/ui/InmuebleCard';
import { useInmuebles } from '../../hooks/useInmuebles';
import { getCategorias } from '../../services/inmuebles';

export default function PropiedadesView() {
  const [searchParams, setSearchParams] = useSearchParams();
  const [categorias, setCategorias] = useState([]);

  const initialFilters = {
    tipo:         searchParams.get('tipo')        ?? '',
    categoria_id: searchParams.get('categoria_id') ?? '',
    precio_min:   searchParams.get('precio_min')   ?? '',
    precio_max:   searchParams.get('precio_max')   ?? '',
  };

  const { inmuebles, meta, loading, error, page, setPage, filters, applyFilters } =
    useInmuebles(initialFilters);

  useEffect(() => {
    getCategorias().then((r) => setCategorias(r.data ?? []));
  }, []);

  const handleFilter = (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const newFilters = Object.fromEntries(
      [...fd.entries()].filter(([, v]) => v !== '')
    );
    applyFilters(newFilters);
    setSearchParams(newFilters);
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

      <h1 className="text-2xl font-bold text-gray-900 mb-6">Propiedades disponibles</h1>

      {/* Filtros */}
      <form onSubmit={handleFilter} className="card p-5 mb-8">
        <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
          <select name="tipo" defaultValue={filters.tipo}
            className="input-field">
            <option value="">Tipo: Todos</option>
            <option value="casa">Casa</option>
            <option value="apto">Apartamento</option>
            <option value="terreno">Terreno</option>
          </select>

          <select name="categoria_id" defaultValue={filters.categoria_id}
            className="input-field">
            <option value="">Categoría: Todas</option>
            {categorias.map((c) => (
              <option key={c.id} value={c.id}>{c.name}</option>
            ))}
          </select>

          <input type="number" name="precio_min" placeholder="Precio mínimo"
            defaultValue={filters.precio_min} className="input-field" min="0" step="1000" />

          <input type="number" name="precio_max" placeholder="Precio máximo"
            defaultValue={filters.precio_max} className="input-field" min="0" step="1000" />

          <button type="submit" className="btn-primary">Buscar</button>
        </div>
      </form>

      {/* Resultados */}
      {error && (
        <div className="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-red-700 text-sm mb-6">
          {error}
        </div>
      )}

      {loading ? (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {Array.from({ length: 6 }).map((_, i) => (
            <div key={i} className="card h-80 animate-pulse bg-gray-100" />
          ))}
        </div>
      ) : inmuebles.length === 0 ? (
        <div className="text-center py-20 text-gray-400">
          <p className="text-lg">No se encontraron propiedades con esos filtros.</p>
        </div>
      ) : (
        <>
          <p className="text-sm text-gray-500 mb-4">
            {meta?.total ?? inmuebles.length} propiedades encontradas
          </p>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {inmuebles.map((inmueble) => (
              <InmuebleCard key={inmueble.id} inmueble={inmueble} />
            ))}
          </div>

          {/* Paginación */}
          {meta && meta.last_page > 1 && (
            <div className="flex items-center justify-center gap-2 mt-10">
              <button onClick={() => setPage((p) => Math.max(1, p - 1))}
                disabled={page === 1} className="btn-outline text-sm !px-3 !py-1.5 disabled:opacity-40">
                ← Anterior
              </button>
              <span className="text-sm text-gray-600">
                Página {meta.current_page} de {meta.last_page}
              </span>
              <button onClick={() => setPage((p) => Math.min(meta.last_page, p + 1))}
                disabled={page === meta.last_page} className="btn-outline text-sm !px-3 !py-1.5 disabled:opacity-40">
                Siguiente →
              </button>
            </div>
          )}
        </>
      )}
    </div>
  );
}
