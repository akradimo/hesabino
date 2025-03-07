/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      "./src/**/*.{php,html,js}",
      "./templates/**/*.{php,html,js}"
    ],
    theme: {
      extend: {
        fontFamily: {
          'vazir': ['Vazir', 'sans-serif'],
        },
        colors: {
          'primary': '#2563eb',
        }
      },
    },
    plugins: [
      require('@tailwindcss/forms'),
    ],
  }