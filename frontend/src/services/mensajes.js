import api from './api';

/**
 * Envía el formulario de contacto al backend.
 * @param {{ nombre, email, telefono?, mensaje, inmueble_id?, tipo }} data
 */
export const enviarMensaje = (data) =>
  api.post('/mensajes', data).then((r) => r.data);
