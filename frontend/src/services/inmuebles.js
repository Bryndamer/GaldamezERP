import api from './api';

/**
 * Obtiene el listado paginado de inmuebles disponibles.
 * @param {Object} params - precio_min, precio_max, categoria_id, tipo, habitaciones_min, page
 */
export const getInmuebles = (params = {}) =>
  api.get('/inmuebles', { params }).then((r) => r.data);

/**
 * Obtiene el detalle de un inmueble por ID.
 * @param {number|string} id
 */
export const getInmueble = (id) =>
  api.get(`/inmuebles/${id}`).then((r) => r.data);

/**
 * Obtiene las categorías disponibles para los filtros.
 */
export const getCategorias = () =>
  api.get('/categorias').then((r) => r.data);
