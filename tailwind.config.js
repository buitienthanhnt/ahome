import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
// dùng qua:  @material-tailwind/react [https://www.material-tailwind.com/docs/react/installation]
// Nếu không dễ bị lỗi về font, css liên quan tới: @material-tailwind
const withMT = require("@material-tailwind/react/utils/withMT");

/** @type {import('tailwindcss').Config} */
export default withMT({
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{js,ts,jsx,tsx}',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: { // custom add
                "3xl": "0 35px 60px -15px rgba(0, 0, 0, 0.3)",
            },
        },
    },

    plugins: [forms],
});


// https://www.material-tailwind.com/docs/react/screens
// Breakpoint prefix	Minimum width	CSS
// sm	40rem (640px)	@media (width >= 40rem) { ... }
// md	48rem (768px)	@media (width >= 48rem) { ... }
// lg	64rem (1024px)	@media (width >= 64rem) { ... }
// xl	80rem (1280px)	@media (width >= 80rem) { ... }
// 2xl	96rem (1536px)	@media (width >= 96rem) { ... }
