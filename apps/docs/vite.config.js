import { fileURLToPath, URL } from 'node:url';
import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

const html = (name) => fileURLToPath(new URL(`./${name}.html`, import.meta.url));

export default defineConfig({
    plugins: [
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@ui': fileURLToPath(new URL('../../packages/ui', import.meta.url)),
        },
    },
    build: {
        rollupOptions: {
            input: {
                index: html('index'),
                about: html('about'),
                installation: html('installation'),
                theming: html('theming'),
                components: html('components'),
                accordion: html('accordion'),
                alert: html('alert'),
                banner: html('banner'),
                button: html('button'),
                card: html('card'),
                checkbox: html('checkbox'),
                datepicker: html('datepicker'),
                dropdown: html('dropdown'),
                'form-field': html('form-field'),
                icon: html('icon'),
                input: html('input'),
                radio: html('radio'),
                select: html('select'),
                spinner: html('spinner'),
                textarea: html('textarea'),
                toggle: html('toggle'),
            },
        },
    },
    server: {
        port: 3000,
        open: false,
    },
});
