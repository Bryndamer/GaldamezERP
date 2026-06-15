import { ThemeProvider } from '../context/ThemeContext';
import Navbar from './layout/Navbar';
import Footer from './layout/Footer';

export default function Layout({ children }) {
  return (
    <ThemeProvider>
      <div className="min-h-screen flex flex-col bg-white dark:bg-gray-950 transition-colors duration-200">
        <Navbar />
        <main className="flex-1">
          {children}
        </main>
        <Footer />
      </div>
    </ThemeProvider>
  );
}
