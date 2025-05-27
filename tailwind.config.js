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
      },
    },
  },
  plugins: [],
}
