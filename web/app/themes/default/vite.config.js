import { defineConfig } from 'vite';
import autoprefixer from 'autoprefixer';
import { browserslistToTargets } from 'lightningcss';
import browserslist from 'browserslist';
import { purgeCSSPlugin } from '@fullhuman/postcss-purgecss';
import path from 'path';

const refreshPlugin = {
    name: 'refresh-plugin',
    handleHotUpdate({ file, server }) {
        if (file.endsWith('.php') || file.endsWith('.twig')) {
            server.hot.send({
                type: 'full-reload'
            });
            console.log(`[vite] ${path.basename(file)} changed, reloading...`);
        }
    },
}

export default defineConfig(({ mode }) => ({
    base: mode === 'production' ? `/app/themes/default/dist/` : '/',
    publicDir: '',
    plugins: [refreshPlugin],
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true,
                silenceDeprecations: ['import'],
            }
        },
        transformer: 'lightningcss',
        lightningcss: {
            targets: browserslistToTargets(
                browserslist('>= 0.25%')
            ),
        },
        postcss: {
            plugins: [
                autoprefixer(),
                purgeCSSPlugin({
                    content: [
                        path.resolve(__dirname, 'assets/js/**/*.js'),
                        path.resolve(__dirname, 'views/**/*.twig'),
                    ],
                    defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || [],
                })
            ]
        }
    },
    resolve: {
        alias: {
            '~': path.resolve(__dirname, 'node_modules'),
            '@img': path.resolve(__dirname, 'assets/img'),
            '@js': path.resolve(__dirname, 'assets/js'),
            '@scss': path.resolve(__dirname, 'assets/scss'),
        }
    },
    server: {
        port: 1337,
        host: 'localhost',
        watch: {
            disableGlobbing: false,
            usePolling: false,
            ignored: [
                '**/node_modules/**',
                '**/vendor/**',
            ]
        },
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
