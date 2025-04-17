import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        // Generate source maps for better debugging
        sourcemap: false,
        // Enable minification for production builds
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
        // Configure chunk splitting for better caching
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs', 'axios'],
                },
                // Customize chunk filenames
                chunkFileNames: 'assets/js/[name]-[hash].js',
                entryFileNames: 'assets/js/[name]-[hash].js',
                assetFileNames: 'assets/[ext]/[name]-[hash].[ext]',
            },
        },
        // Optimize dependencies
        commonjsOptions: {
            include: [/node_modules/],
        },
        // Reduce chunk size warnings threshold
        chunkSizeWarningLimit: 1000,
    },
    // Optimize dependency pre-bundling
    optimizeDeps: {
        include: ['alpinejs', 'axios'],
    },
});
