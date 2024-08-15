/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: "class", // Mengaktifkan mode gelap berbasis kelas
  content: [
    "./src/**/*.{html,php}", // Atur jalur konten sesuai dengan struktur proyekmu
  ],
  theme: {
    extend: {
      colors: {
        // Tambahkan warna khusus di sini jika diperlukan
      },
    },
  },
  plugins: [],
};
