import { Routes, Route } from 'react-router-dom';
import Layout from './components/Layout';
import HomeView from './views/Home/HomeView';
import PropiedadesView from './views/Propiedades/PropiedadesView';
import PropiedadDetalleView from './views/Propiedades/PropiedadDetalleView';
import ContactoView from './views/Contacto/ContactoView';

export default function App() {
  return (
    <Layout>
      <Routes>
        <Route path="/"                element={<HomeView />} />
        <Route path="/propiedades"     element={<PropiedadesView />} />
        <Route path="/propiedades/:id" element={<PropiedadDetalleView />} />
        <Route path="/contacto"        element={<ContactoView />} />
        <Route path="*" element={
          <div className="flex items-center justify-center h-64 text-gray-400">
            <p>Página no encontrada.</p>
          </div>
        } />
      </Routes>
    </Layout>
  );
}
