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
        'brand-red':  '#E21C25',
        'brand-blue': '#003A70',
        'brand-gray': '#F5F7FA',
        // Dark theme palette
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
        // Glass colors
        glass: {
          white: 'rgba(255, 255, 255, 0.05)',
          'white-10': 'rgba(255, 255, 255, 0.10)',
          'white-20': 'rgba(255, 255, 255, 0.20)',
          border: 'rgba(255, 255, 255, 0.08)',
          'border-light': 'rgba(255, 255, 255, 0.12)',
        },
        // Accent colors
        accent: {
          red: '#E21C25',
          'red-glow': 'rgba(226, 28, 37, 0.4)',
          blue: '#3B82F6',
          green: '#10B981',
          amber: '#F59E0B',
        },
      },
      backdropBlur: {
        xs: '2px',
        '2xl': '40px',
        '3xl': '64px',
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
          '0%, 100%': { boxShadow: '0 0 5px rgba(226, 28, 37, 0.3)' },
          '50%': { boxShadow: '0 0 20px rgba(226, 28, 37, 0.6)' },
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
