import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                // Sneat Primary (Indigo/Purple)
                primary: {
                    50: '#e7e7ff',
                    100: '#d4d5ff',
                    200: '#b0b1ff',
                    300: '#8c8eff',
                    400: '#7b7dff',
                    500: '#696cff',
                    600: '#5a5de6',
                    700: '#4b4ecc',
                    800: '#3d40b3',
                    900: '#2e3199',
                },
                // Sneat Secondary (Green)
                secondary: {
                    50: '#ECFDF5',
                    100: '#D1FAE5',
                    200: '#A7F3D0',
                    300: '#6EE7B7',
                    400: '#34D399',
                    500: '#10B981',
                    600: '#059669',
                    700: '#047857',
                    800: '#065F46',
                    900: '#064E3B',
                },
                // Sneat-specific tokens for quick access
                sneat: {
                    'body-light': '#f5f5f9',
                    'body-dark': '#232333',
                    'card-light': '#ffffff',
                    'card-dark': '#2b2c40',
                    'heading-light': '#566a7f',
                    'heading-dark': '#d5d5e2',
                    'text-light': '#697a8d',
                    'text-dark': '#a1b0cb',
                    'border-light': '#d9dee3',
                    'border-dark': '#434463',
                },
            },
            fontFamily: {
                heading: ['Poppins', ...defaultTheme.fontFamily.sans],
                body: ['Public Sans', 'Lato', ...defaultTheme.fontFamily.sans],
                sans: ['Public Sans', 'Lato', ...defaultTheme.fontFamily.sans],
            },
            animation: {
                'fade-up': 'fadeUp 0.7s ease forwards',
                'fade-in': 'fadeIn 0.5s ease forwards',
                'slide-left': 'slideLeft 0.6s ease forwards',
                'slide-right': 'slideRight 0.6s ease forwards',
                'float': 'float 6s ease-in-out infinite',
                'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
            },
            keyframes: {
                fadeUp: {
                    '0%': { opacity: '0', transform: 'translateY(40px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-40px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                slideRight: {
                    '0%': { opacity: '0', transform: 'translateX(40px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
                pulseGlow: {
                    '0%, 100%': { boxShadow: '0 0 20px rgba(105, 108, 255, 0.2)' },
                    '50%': { boxShadow: '0 0 40px rgba(105, 108, 255, 0.4)' },
                },
            },
            boxShadow: {
                'sneat': '0 2px 6px 0 rgba(67, 89, 113, 0.12)',
                'sneat-lg': '0 4px 18px 0 rgba(67, 89, 113, 0.1)',
                'sneat-dark': '0 2px 6px 0 rgba(0, 0, 0, 0.25)',
                'sneat-dark-lg': '0 4px 18px 0 rgba(0, 0, 0, 0.25)',
            },
        },
    },

    plugins: [forms],
};
