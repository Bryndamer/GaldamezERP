import { render, screen, fireEvent } from '@testing-library/react';
import { ThemeProvider, useTheme } from '../context/ThemeContext';

function ToggleButton() {
  const { theme, toggleTheme } = useTheme();
  return (
    <button onClick={toggleTheme} data-testid="toggle">
      Tema: {theme}
    </button>
  );
}

describe('ThemeContext', () => {
  beforeEach(() => {
    document.documentElement.classList.remove('dark');
    localStorage.clear();
  });

  it('inicia en modo claro cuando el sistema no prefiere dark', () => {
    render(<ThemeProvider><ToggleButton /></ThemeProvider>);
    expect(screen.getByText('Tema: light')).toBeInTheDocument();
    expect(document.documentElement.classList.contains('dark')).toBe(false);
  });

  it('toggle añade la clase dark al elemento <html>', () => {
    render(<ThemeProvider><ToggleButton /></ThemeProvider>);
    fireEvent.click(screen.getByTestId('toggle'));
    expect(document.documentElement.classList.contains('dark')).toBe(true);
  });

  it('un segundo toggle vuelve al modo claro', () => {
    render(<ThemeProvider><ToggleButton /></ThemeProvider>);
    const btn = screen.getByTestId('toggle');
    fireEvent.click(btn);
    fireEvent.click(btn);
    expect(document.documentElement.classList.contains('dark')).toBe(false);
  });

  it('persiste el tema seleccionado en localStorage', () => {
    render(<ThemeProvider><ToggleButton /></ThemeProvider>);
    fireEvent.click(screen.getByTestId('toggle'));
    expect(localStorage.getItem('galdamez-theme')).toBe('dark');
  });

  it('lanza error si useTheme se usa fuera de ThemeProvider', () => {
    const original = console.error;
    console.error = () => {};
    expect(() => render(<ToggleButton />)).toThrow();
    console.error = original;
  });
});
