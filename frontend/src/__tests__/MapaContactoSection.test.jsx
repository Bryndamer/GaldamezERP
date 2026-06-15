import { render, screen } from '@testing-library/react';
import MapaContactoSection from '../components/sections/MapaContactoSection';

describe('MapaContactoSection', () => {
  beforeEach(() => {
    render(<MapaContactoSection />);
  });

  it('muestra el título "Visítenos o escríbanos"', () => {
    expect(screen.getByText('Visítenos o escríbanos')).toBeInTheDocument();
  });

  it('muestra los números de teléfono', () => {
    expect(screen.getByText('2301-1727')).toBeInTheDocument();
    expect(screen.getByText('2335-2608')).toBeInTheDocument();
  });

  it('muestra el número de WhatsApp', () => {
    expect(screen.getByText('+503 7799-1711')).toBeInTheDocument();
  });

  it('muestra el correo electrónico', () => {
    expect(screen.getByText('info@bienesraicescentroamerica.com')).toBeInTheDocument();
  });

  it('incluye un iframe con el mapa de Google Maps', () => {
    const iframe = document.querySelector('iframe');
    expect(iframe).toBeInTheDocument();
    expect(iframe.src).toContain('maps.google.com');
  });

  it('muestra el horario de atención', () => {
    expect(screen.getByText(/Lun – Vie/)).toBeInTheDocument();
  });
});
