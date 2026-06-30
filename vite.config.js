import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel([
            'resources/css/user.css',
            'resources/js/user.js',
        ]),
        tailwindcss(),
    ],
})
