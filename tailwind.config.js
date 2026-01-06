/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        // Kita pindahkan settingan font dari CSS tadi ke sini
        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'], 
      },
    },
  },
  plugins: [],
}