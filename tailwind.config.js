/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: "class",
  content: [
    "./backend/views/**/*.{html,php}",
    "./backend/views/**/*.php",
    "./node_modules/flowbite/**/*.js",
  ],
  safelist: ["bg-rose-500", "hover:bg-rose-600", "text-white"],
  theme: {
    extend: {
      colors: {
        black: "#272727",
      },
    },
  },
  plugins: [require("flowbite/plugin")],
};
