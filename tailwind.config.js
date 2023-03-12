/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./views/**/*.{php,html,js}", "./src/**/*.css"],
  theme: {
    listStyleType: {
      none: 'none',
      disc: 'disc',
      square: 'square',
    },
    extend: {
      maxWidth: {
        '8xl': '90rem'
      },
    },
  },
  plugins: [],
}