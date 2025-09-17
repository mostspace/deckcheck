import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f7fdf0',
                    100: '#eefbe0',
                    200: '#ddf7c1',
                    300: '#c5f096',
                    400: '#a8e465',
                    500: '#B8EC27', // Main primary color
                    600: '#a6d41e',
                    700: '#8bb018',
                    800: '#6f8a1a',
                    900: '#5a6f1a',
                    950: '#2f3a0a',
                },
                accent: {
                    50: '#f7fdf0',
                    100: '#eefbe0',
                    200: '#ddf7c1',
                    300: '#c5f096',
                    400: '#a8e465',
                    500: '#B8EC27', // Same as primary
                    600: '#a6d41e',
                    700: '#8bb018',
                    800: '#6f8a1a',
                    900: '#5a6f1a',
                    950: '#2f3a0a',
                }
            }
        },
    },

    plugins: [forms],
};
