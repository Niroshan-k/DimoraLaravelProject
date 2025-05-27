import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                reverie: ['REVERIE', 'sans-serif']
            },
            colors:{
                yellow : {
                    50  : '#EFEFE9'
                },
                green : {
                    50  : '#959D90',
                    100 : '#223030'
                },
                brown : {
                    50  : '#E8D9CD',
                    100 : '#BBA58F',
                    150 : '#523D35'
                },
                red : {
                    100 : '#FF4600'
                }
            },
        },
    },

    plugins: [forms, typography],
};
