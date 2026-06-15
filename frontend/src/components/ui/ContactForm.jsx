import { useState } from 'react';
import { Send, CheckCircle } from 'lucide-react';
import { enviarMensaje } from '../../services/mensajes';

const initial = { nombre: '', email: '', telefono: '', mensaje: '', tipo: 'contacto' };

const inputBase =
  'w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 ' +
  'text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 ' +
  'px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition';

const inputError =
  'border-red-400 dark:border-red-500 bg-red-50 dark:bg-red-900/20';

export default function ContactForm({ inmuebleId = null, compact = false }) {
  const [form, setForm]       = useState(initial);
  const [errors, setErrors]   = useState({});
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState(false);

  const handleChange = (e) => {
    setForm((f) => ({ ...f, [e.target.name]: e.target.value }));
    if (errors[e.target.name]) setErrors((er) => ({ ...er, [e.target.name]: null }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setErrors({});
    try {
      const payload = { ...form };
      if (inmuebleId) payload.inmueble_id = Number(inmuebleId);
      await enviarMensaje(payload);
      setSuccess(true);
      setForm(initial);
    } catch (err) {
      if (err?.response?.status === 422) {
        setErrors(err.response.data.errors ?? {});
      } else if (err?.response?.status === 429) {
        setErrors({ general: 'Demasiados mensajes. Espera un momento antes de intentarlo de nuevo.' });
      } else {
        setErrors({ general: 'Error al enviar el mensaje. Intenta de nuevo.' });
      }
    } finally {
      setLoading(false);
    }
  };

  if (success) return (
    <div className="text-center py-8">
      <div className="h-14 w-14 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
        <CheckCircle className="h-7 w-7 text-green-600 dark:text-green-400" />
      </div>
      <h3 className="font-bold text-gray-900 dark:text-white mb-1">¡Mensaje enviado!</h3>
      <p className="text-sm text-gray-500 dark:text-gray-400 mb-4">
        Un agente te contactará pronto al correo indicado.
      </p>
      <button
        onClick={() => setSuccess(false)}
        className="text-sm text-amber-600 dark:text-amber-400 hover:underline font-medium"
      >
        Enviar otro mensaje
      </button>
    </div>
  );

  return (
    <form onSubmit={handleSubmit} className="space-y-4" noValidate>

      {errors.general && (
        <div className="rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700
                        px-4 py-3 text-sm text-red-700 dark:text-red-400">
          {errors.general}
        </div>
      )}

      {inmuebleId && (
        <p className="text-xs text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20
                      border border-amber-100 dark:border-amber-800 rounded-lg px-3 py-2">
          Consultando sobre la propiedad #{inmuebleId}
        </p>
      )}

      <div>
        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Nombre <span className="text-red-500">*</span>
        </label>
        <input
          name="nombre"
          value={form.nombre}
          onChange={handleChange}
          placeholder="Tu nombre completo"
          className={`${inputBase} ${errors.nombre ? inputError : ''}`}
        />
        {errors.nombre && <p className="mt-1 text-xs text-red-600 dark:text-red-400">{errors.nombre[0]}</p>}
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Correo electrónico <span className="text-red-500">*</span>
        </label>
        <input
          type="email"
          name="email"
          value={form.email}
          onChange={handleChange}
          placeholder="correo@ejemplo.com"
          className={`${inputBase} ${errors.email ? inputError : ''}`}
        />
        {errors.email && <p className="mt-1 text-xs text-red-600 dark:text-red-400">{errors.email[0]}</p>}
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Teléfono
        </label>
        <input
          type="tel"
          name="telefono"
          value={form.telefono}
          onChange={handleChange}
          placeholder="+503 7000-0000"
          className={inputBase}
        />
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Mensaje <span className="text-red-500">*</span>
        </label>
        <textarea
          name="mensaje"
          value={form.mensaje}
          onChange={handleChange}
          rows={compact ? 3 : 4}
          placeholder="¿En qué podemos ayudarte?"
          className={`${inputBase} resize-none ${errors.mensaje ? inputError : ''}`}
        />
        {errors.mensaje && <p className="mt-1 text-xs text-red-600 dark:text-red-400">{errors.mensaje[0]}</p>}
      </div>

      <button
        type="submit"
        disabled={loading}
        className="btn-primary w-full flex items-center justify-center gap-2"
      >
        {loading
          ? 'Enviando...'
          : <><Send className="h-4 w-4" /> Enviar consulta</>
        }
      </button>
    </form>
  );
}
