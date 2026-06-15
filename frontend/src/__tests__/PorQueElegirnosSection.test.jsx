import { render, screen } from '@testing-library/react';
import PorQueElegirnosSection from '../components/sections/PorQueElegirnosSection';

describe('PorQueElegirnosSection', () => {
  beforeEach(() => {
    render(<PorQueElegirnosSection />);
  });

  it('muestra el label de sección "Por qué elegirnos"', () => {
    expect(screen.getByText(/Por qué elegirnos/i)).toBeInTheDocument();
  });

  it('muestra el título con los 25 años', () => {
    expect(screen.getByText(/Más de 25 años/)).toBeInTheDocument();
  });

  it('muestra la tarjeta Experiencia Garantizada', () => {
    expect(screen.getByText('Experiencia Garantizada')).toBeInTheDocument();
  });

  it('muestra la tarjeta Facilidades de Financiamiento', () => {
    expect(screen.getByText('Facilidades de Financiamiento')).toBeInTheDocument();
  });

  it('muestra la tarjeta Atención Personalizada', () => {
    expect(screen.getByText('Atención Personalizada')).toBeInTheDocument();
  });

  it('renderiza exactamente 3 tarjetas', () => {
    const headings = screen.getAllByRole('heading', { level: 3 });
    expect(headings).toHaveLength(3);
  });
});
