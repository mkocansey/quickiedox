/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors')
module.exports = {
  content: ["./views/**/*.{php,html,js}", "./src/**/*.css"],
  theme: {
    listStyleType: {
      none: 'none',
      disc: 'disc',
      square: 'square',
    },
    extend: {
      colors: {
        primary: colors.indigo,
      },
      maxWidth: {
        '8xl': '90rem'
      },
    },
  },
  plugins: [],
}