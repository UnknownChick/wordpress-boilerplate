import { defineConfig } from 'vite';
import autoprefixer from 'autoprefixer';
import { browserslistToTargets } from 'lightningcss';
import browserslist from 'browserslist';
import { purgeCSSPlugin } from '@fullhuman/postcss-purgecss';
import path from 'path';

const phpRefreshPlugin = {
    name: 'php',
    handleHotUpdate({ file, server }) {
        if (file.endsWith('.php') || file.endsWith('.twig')) {
            server.hot.send({
                type: 'full-reload'
            });
        }
    },
}

export default defineConfig(({ mode }) => ({
    base: mode === 'production' ? `/app/themes/default/dist/` : '/',
    publicDir: '',
    plugins: [phpRefreshPlugin],
    resolve: {
        alias: {
            '~': path.resolve(__dirname, 'node_modules'),
        }
    },
    css: {
        transformer: 'postcss',
        lightningcss: {
            targets: browserslistToTargets(browserslist('>= 0.25%'))
        },
        postcss: {
            plugins: [
                autoprefixer({}),
                // purgeCSSPlugin({
                //     content: [
                //         './**/*.php',
                //         './**/*.twig',
                //     ],
                //     safelist: {
                //         standard: [
                //             ':is',
                //             ':has',
                //             ':where',
                //             /^btn/,
                //             /^list/,
                //             'active',
                //             'hidden'
                //         ]
                //     },
                // })
            ],
        },
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler'
            }
        }
    },
    server: {
        port: 1337,
        host: '0.0.0.0',
        watch: {
            disableGlobbing: false,
            usePolling: true
        },
        allowedHosts: true,
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, PATCH, OPTIONS',
            'Access-Control-Allow-Headers': 'Content-Type, Authorization, X-Requested-With, Accept, Origin, Referer, User-Agent',
        }
    },
    build: {
        copyPublicDir: false,
        cssMinify: 'lightningcss',
        rollupOptions: {
            input: {
                style: './assets/scss/style.scss',
                app: './assets/js/app.js',
            },
            output: {
                dir: path.resolve(__dirname, 'dist'),
                format: 'es',
                entryFileNames: `[name].js`,
                chunkFileNames: `[name].js`,
                assetFileNames: `[name].[ext]`,
            },
        },
    },
}));
