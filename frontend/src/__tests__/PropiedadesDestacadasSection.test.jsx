import { render, screen } from '@testing-library/react';
import { MemoryRouter } from 'react-router-dom';
import { vi } from 'vitest';
import PropiedadesDestacadasSection from '../components/sections/PropiedadesDestacadasSection';

vi.mock('../hooks/useInmuebles', () => ({
  useInmuebles: () => ({
    inmuebles: [
      {
        id: 1,
        titulo: 'Casa en Chalatenango',
        precio: 45000,
        tipo: 'casa',
        habitaciones: 3,
        banos: 2,
        metraje: 120,
        estado: 'disponible',
        fotos: [],
        categoria: { name: 'Residencial' },
      },
    ],
    loading: false,
    error: null,
  }),
}));

describe('PropiedadesDestacadasSection', () => {
  function Wrapper({ children }) {
    return <MemoryRouter>{children}</MemoryRouter>;
  }

  it('muestra el título "Propiedades destacadas"', () => {
    render(<PropiedadesDestacadasSection />, { wrapper: Wrapper });
    expect(screen.getByText('Propiedades destacadas')).toBeInTheDocument();
  });

  it('muestra el link "Ver todas"', () => {
    render(<PropiedadesDestacadasSection />, { wrapper: Wrapper });
    expect(screen.getByRole('link', { name: /ver todas/i })).toBeInTheDocument();
  });

  it('renderiza la tarjeta de la propiedad mockeada', () => {
    render(<PropiedadesDestacadasSection />, { wrapper: Wrapper });
    expect(screen.getByText('Casa en Chalatenango')).toBeInTheDocument();
  });

  it('muestra el precio de la propiedad', () => {
    render(<PropiedadesDestacadasSection />, { wrapper: Wrapper });
    expect(screen.getByText(/45,000/)).toBeInTheDocument();
  });
});
