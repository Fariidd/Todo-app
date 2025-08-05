/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', // 👈 active le dark mode en ajoutant une classe CSS
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
