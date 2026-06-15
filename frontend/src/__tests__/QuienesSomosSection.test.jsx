import { render, screen } from '@testing-library/react';
import QuienesSomosSection from '../components/sections/QuienesSomosSection';

describe('QuienesSomosSection', () => {
  beforeEach(() => {
    render(<QuienesSomosSection />);
  });

  it('muestra el titular de la sección', () => {
    expect(screen.getByText(/Quiénes somos/i)).toBeInTheDocument();
  });

  it('menciona los 25 años de experiencia', () => {
    expect(screen.getByText(/25 años/)).toBeInTheDocument();
  });

  it('muestra "Chalatenango" en la sección', () => {
    expect(screen.getByText(/Chalatenango/)).toBeInTheDocument();
  });

  it('muestra el correo electrónico corporativo', () => {
    expect(screen.getByText(/info@bienesraicescentroamerica\.com/)).toBeInTheDocument();
  });

  it('muestra el número de WhatsApp', () => {
    expect(screen.getByText(/7799-1711/)).toBeInTheDocument();
  });

  it('tiene el placeholder visual de la foto', () => {
    expect(screen.getByText(/FOTOGRAFÍA DE LA OFICINA/i)).toBeInTheDocument();
  });
});
