const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    prefix: 'tw-',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        screens: {
            'sm': '576px',
            'md': '768px',
            'lg': '992px',
            'xl': '1200px',
            '2xl': '1400px',
        },
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        require('@tailwindcss/line-clamp'),
    ],

    corePlugins: {
        preflight: false,
    },
};
