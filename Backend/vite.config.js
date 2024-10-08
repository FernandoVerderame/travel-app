import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
const path = require('path') // <-- require path from node

export default defineConfig({
    plugins: [
        laravel({
            // edit the first value of the array input to point to our new sass files and folder.
            input: [
                'resources/scss/app.scss',
                'resources/js/app.js',
                'resources/js/delete_confirmation.js',
                'resources/js/image_preview.js',
                'resources/js/slug.js',
                'resources/js/address.js',
                'resources/js/map.js',
                'resources/js/tooltip.js',
                'resources/js/description_modal.js'
            ],
            refresh: true,
        }),
    ],
    // Add resolve object and aliases
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~resources': '/resources/'
        }
    }
});
