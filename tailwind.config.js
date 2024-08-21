/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: "class",
  content: [
    "./backend/views/**/*.{html,php}",
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        black: "#272727",
      },
    },
  },
  plugins: [require("flowbite/plugin")],
};
