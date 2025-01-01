/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        'button-color-1': '#0284c7', // If you need to override or customize
        'main-theme-color': '#9D7764',
        'secondary-theme-color-2': '#FFDE21',
        'theme-color-2': '#BC9E88',
        'background-color-1': '#F0F0E5',
        'button-color-1': '#091235',
        'button-color-1-hover': '#FFDE21',
        'text-color-1': '#F0F0E5',
        'text-color-1-hover': '#FFDE21',
        'broken-white-color': '#F1F0EB',
        //'button-color-1': '#091235',
      },
      fontFamily: {
        serif: ['Merriweather', 'serif'],
      },
    },
  },
  plugins: [],
}