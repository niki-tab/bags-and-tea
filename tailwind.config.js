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
        'theme-color-1': '#CB8176',
        'theme-color-2': '#482626',
        'theme-color-3': '#C12637',
        'theme-color-4': '#F7F0ED',
        'background-color-1': '#CB8176',
        'background-color-2': '#482626',
        'background-color-3': '#C12637',
        'background-color-4': '#F7F0ED',
        'background-color-5': '#CA2530',
        'button-color-1': '#091235',
        'button-color-1-hover': '#FFDE21',
        'color-1': '#CB8176',
        'color-2': '#482626',
        'color-3': '#C12637',
        'color-4': '#F7F0ED',
        'color-5': '#CA2530',
        'color-6': '#A47864',
        'text-color-1-hover': '#FFDE21',
        'broken-white-color': '#F1F0EB',
        //'button-color-1': '#091235',
      },
      fontFamily: {
        serif: ['Merriweather', 'serif'],
        robotoCondensed: ['"Roboto Condensed"', 'sans-serif'],
      },
    },
  },
  plugins: [],
}