import { render, screen } from '@testing-library/react';
import { MemoryRouter } from 'react-router-dom';
import { ThemeProvider } from '../context/ThemeContext';
import Navbar from '../components/layout/Navbar';

function Wrapper({ children }) {
  return (
    <MemoryRouter>
      <ThemeProvider>{children}</ThemeProvider>
    </MemoryRouter>
  );
}

describe('Navbar', () => {
  beforeEach(() => {
    document.documentElement.classList.remove('dark');
    localStorage.clear();
  });

  it('renderiza el nombre de la empresa', () => {
    render(<Navbar />, { wrapper: Wrapper });
    expect(screen.getByText(/Galdámez/)).toBeInTheDocument();
  });

  it('muestra el link de Inicio', () => {
    render(<Navbar />, { wrapper: Wrapper });
    expect(screen.getByRole('link', { name: 'Inicio' })).toBeInTheDocument();
  });

  it('muestra el link de Propiedades', () => {
    render(<Navbar />, { wrapper: Wrapper });
    expect(screen.getByRole('link', { name: 'Propiedades' })).toBeInTheDocument();
  });

  it('muestra el link de Contacto', () => {
    render(<Navbar />, { wrapper: Wrapper });
    expect(screen.getByRole('link', { name: 'Contacto' })).toBeInTheDocument();
  });

  it('tiene el botón para cambiar el tema', () => {
    render(<Navbar />, { wrapper: Wrapper });
    expect(screen.getByTestId('theme-toggle')).toBeInTheDocument();
  });
});
