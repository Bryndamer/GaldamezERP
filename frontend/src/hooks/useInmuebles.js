import { useState, useEffect, useCallback } from 'react';
import { getInmuebles } from '../services/inmuebles';

/**
 * Hook para obtener el listado de inmuebles con filtros y paginación.
 * Vuelve a hacer fetch cada vez que cambian los filtros o la página.
 */
export function useInmuebles(initialFilters = {}) {
  const [data, setData]       = useState(null);   // respuesta completa (data + meta + links)
  const [loading, setLoading] = useState(false);
  const [error, setError]     = useState(null);
  const [filters, setFilters] = useState(initialFilters);
  const [page, setPage]       = useState(1);

  const fetchInmuebles = useCallback(async () => {
    setLoading(true);
    setError(null);
    try {
      const response = await getInmuebles({ ...filters, page });
      setData(response);
    } catch (err) {
      setError(err?.response?.data?.message ?? 'Error al cargar inmuebles.');
    } finally {
      setLoading(false);
    }
  }, [filters, page]);

  useEffect(() => {
    fetchInmuebles();
  }, [fetchInmuebles]);

  const applyFilters = (newFilters) => {
    setFilters(newFilters);
    setPage(1); // resetear paginación al filtrar
  };

  return {
    inmuebles: data?.data ?? [],
    meta: data?.meta ?? null,
    links: data?.links ?? null,
    loading,
    error,
    page,
    setPage,
    filters,
    applyFilters,
  };
}
