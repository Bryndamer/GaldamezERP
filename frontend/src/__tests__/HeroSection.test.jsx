import { render, screen } from '@testing-library/react';
import { MemoryRouter } from 'react-router-dom';
import HeroSection from '../components/sections/HeroSection';

function Wrapper({ children }) {
  return <MemoryRouter>{children}</MemoryRouter>;
}

describe('HeroSection', () => {
  beforeEach(() => {
    render(<HeroSection />, { wrapper: Wrapper });
  });

  it('muestra el titular principal', () => {
    expect(screen.getByText(/Tu propiedad ideal/)).toBeInTheDocument();
  });

  it('muestra el acento en amber "en El Salvador"', () => {
    expect(screen.getByText(/en El Salvador/)).toBeInTheDocument();
  });

  it('muestra el párrafo con los 25 años de experiencia', () => {
    expect(screen.getByText(/25 años/)).toBeInTheDocument();
  });

  it('muestra el placeholder de la fotografía aérea', () => {
    expect(screen.getByText(/FOTOGRAFÍA AÉREA/i)).toBeInTheDocument();
  });

  it('tiene el botón de búsqueda', () => {
    expect(screen.getByRole('button', { name: /buscar/i })).toBeInTheDocument();
  });

  it('tiene un selector de tipo de propiedad', () => {
    expect(screen.getByRole('combobox')).toBeInTheDocument();
  });
});
