/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './resources/js/**/*.js',
    './resources/**/*.html',
  ],
  theme: {
    extend: {
      colors: {
        // Primary palette (teal from habidominicana)
        primary: {
          DEFAULT: '#005046',
          50:  '#E6F2F0',
          100: '#CCE5E1',
          200: '#99CBC3',
          300: '#66B1A5',
          400: '#339787',
          500: '#005046',
          600: '#00463D',
          700: '#003D35',
          800: '#00332C',
          900: '#002A24',
          950: '#001A17',
        },
        // Accent coral (hot deal badges)
        accent: {
          DEFAULT: '#F76B57',
          light: '#FFEEDA',
          red: '#F76B57',
          green: '#25D366',
          amber: '#F59E0B',
          blue: '#3B82F6',
        },
        // Surface colors
        surface: {
          DEFAULT: '#FAFAFA',
          50:  '#FFFFFF',
          100: '#FAFAFA',
          200: '#F3F4F6',
          300: '#E5E7EB',
          400: '#D1D5DB',
          500: '#9CA3AF',
        },
        // Footer mint/sage
        footer: '#D1E0DD',
        // Legacy brand colors
        'brand-red':  '#E21C25',
        'brand-blue': '#003A70',
        'brand-gray': '#F5F7FA',
        // Admin dark palette (kept for admin panel)
        dark: {
          950: '#0a0a0f',
          900: '#0f1117',
          800: '#161822',
          700: '#1e2030',
          600: '#282a3a',
          500: '#3a3d52',
          400: '#5c5f7a',
          300: '#8b8fa8',
        },
        // Admin glass colors
        glass: {
          white: 'rgba(255, 255, 255, 0.05)',
          'white-10': 'rgba(255, 255, 255, 0.10)',
          'white-20': 'rgba(255, 255, 255, 0.20)',
          border: 'rgba(255, 255, 255, 0.08)',
          'border-light': 'rgba(255, 255, 255, 0.12)',
        },
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-out forwards',
        'slide-up': 'slideUp 0.5s ease-out forwards',
        'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
        'equalizer-1': 'equalizer 0.8s ease-in-out infinite',
        'equalizer-2': 'equalizer 0.8s ease-in-out 0.2s infinite',
        'equalizer-3': 'equalizer 0.8s ease-in-out 0.4s infinite',
        'spin-slow': 'spin 3s linear infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        pulseGlow: {
          '0%, 100%': { boxShadow: '0 0 5px rgba(0, 80, 70, 0.2)' },
          '50%': { boxShadow: '0 0 20px rgba(0, 80, 70, 0.4)' },
        },
        equalizer: {
          '0%, 100%': { height: '4px' },
          '50%': { height: '16px' },
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
