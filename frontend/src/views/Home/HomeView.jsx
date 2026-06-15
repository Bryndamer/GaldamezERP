import HeroSection from '../../components/sections/HeroSection';
import PorQueElegirnosSection from '../../components/sections/PorQueElegirnosSection';
import QuienesSomosSection from '../../components/sections/QuienesSomosSection';
import PropiedadesDestacadasSection from '../../components/sections/PropiedadesDestacadasSection';
import MapaContactoSection from '../../components/sections/MapaContactoSection';

export default function HomeView() {
  return (
    <>
      <HeroSection />
      <PorQueElegirnosSection />
      <QuienesSomosSection />
      <PropiedadesDestacadasSection />
      <MapaContactoSection />
    </>
  );
}
