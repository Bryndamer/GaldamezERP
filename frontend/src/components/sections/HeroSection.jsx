import { useState } from 'react';
import { Search, ChevronDown } from 'lucide-react';
import { useNavigate } from 'react-router-dom';

const TIPOS = [
  { value: '',           label: 'Tipo de propiedad' },
  { value: 'casa',       label: 'Casa' },
  { value: 'apto',       label: 'Apartamento' },
  { value: 'terreno',    label: 'Terreno' },
  { value: 'local',      label: 'Local Comercial' },
];

const STATS = [
  { value: '25', unit: 'años', label: 'de experiencia' },
  { value: 'Zona Norte', unit: '', label: 'El Salvador' },
  { value: '100%', unit: '', label: 'Procesos seguros' },
];

export default function HeroSection() {
  const navigate = useNavigate();
  const [tipo, setTipo] = useState('');

  const handleBuscar = (e) => {
    e.preventDefault();
    navigate(tipo ? `/propiedades?tipo=${tipo}` : '/propiedades');
  };

  return (
    <section className="relative min-h-[88vh] flex items-center overflow-hidden">

      {/* Fondo / Placeholder de fotografía */}
      <div className="absolute inset-0 bg-slate-900 flex items-center justify-center">
        <div className="border border-dashed border-slate-700 rounded-xl px-8 py-6 max-w-sm text-center">
          <p className="text-slate-600 text-xs font-mono tracking-wider leading-relaxed">
            [ AQUÍ SE INCLUIRÁ FOTOGRAFÍA AÉREA O PANORÁMICA<br />DE CHALATENANGO, EL SALVADOR ]
          </p>
        </div>
      </div>

      {/* Overlay gradiente — más denso a la izquierda */}
      <div className="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/88 to-slate-900/30" />

      {/* Contenido */}
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 w-full">
        <div className="max-w-2xl">

          <span className="section-label mb-6 inline-block">
            Bienes Raíces · Chalatenango · El Salvador
          </span>

          <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-5">
            Tu propiedad ideal<br />
            <span className="text-amber-400">en El Salvador</span>
          </h1>

          <p className="text-slate-300 text-lg mb-10 leading-relaxed max-w-lg">
            25 años como empresa líder en bienes raíces del norte de El Salvador.
            Procesos jurídicos y técnicos garantizados para cada cliente.
          </p>

          {/* Buscador */}
          <form
            onSubmit={handleBuscar}
            className="flex flex-col sm:flex-row gap-3 p-2 bg-white/10 backdrop-blur-sm
                       rounded-2xl border border-white/10 max-w-lg"
          >
            <select
              value={tipo}
              onChange={(e) => setTipo(e.target.value)}
              className="flex-1 bg-transparent text-white px-4 py-3 text-sm outline-none
                         [&>option]:text-gray-900 [&>option]:bg-white"
            >
              {TIPOS.map(({ value, label }) => (
                <option key={value} value={value}>{label}</option>
              ))}
            </select>
            <button
              type="submit"
              className="flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-400
                         text-slate-900 font-bold px-6 py-3 rounded-xl transition-colors text-sm"
            >
              <Search className="h-4 w-4" />
              Buscar
            </button>
          </form>

          {/* Stats */}
          <div className="flex flex-wrap gap-10 mt-14 pt-8 border-t border-white/10">
            {STATS.map(({ value, unit, label }) => (
              <div key={label}>
                <p className="text-2xl font-bold text-amber-400">
                  {value}<span className="text-lg ml-1">{unit}</span>
                </p>
                <p className="text-slate-400 text-sm mt-0.5">{label}</p>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Scroll indicator */}
      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/30 animate-bounce">
        <ChevronDown className="h-6 w-6" />
      </div>
    </section>
  );
}
