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
                jakarta: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                display: ['Sora', ...defaultTheme.fontFamily.sans],
                montserrat: ['Montserrat', 'sans-serif'],
            },
            keyframes: {
                'vistoo-breathe': {
                    '0%, 100%': { opacity: '0.75', transform: 'scale(1)' },
                    '50%': { opacity: '1', transform: 'scale(1.04)' },
                },
                'vistoo-float': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                'vistoo-gradient': {
                    '0%, 100%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                },
                'vistoo-shine': {
                    '0%': { transform: 'translateX(-100%) skewX(-12deg)' },
                    '100%': { transform: 'translateX(200%) skewX(-12deg)' },
                },
            },
            animation: {
                'vistoo-breathe': 'vistoo-breathe 14s ease-in-out infinite',
                'vistoo-float': 'vistoo-float 5.5s ease-in-out infinite',
                'vistoo-float-delayed': 'vistoo-float 6s ease-in-out 2s infinite',
                'vistoo-gradient': 'vistoo-gradient 8s ease infinite',
                'vistoo-shine': 'vistoo-shine 2.5s ease-in-out infinite',
            },
        },
    },

    plugins: [forms],
};
