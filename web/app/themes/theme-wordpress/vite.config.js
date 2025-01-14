import { defineConfig } from 'vite';
import autoprefixer from 'autoprefixer';
import { browserslistToTargets } from 'lightningcss';
import browserslist from 'browserslist';
import purgecss from '@fullhuman/postcss-purgecss';
import dotenv from 'dotenv';
import path from 'path';

dotenv.config({ path: '../../../../.env.local' });

const phpRefreshPlugin = {
    name: 'php',
    handleHotUpdate({ file, server }) {
        if (file.endsWith('.php')) {
            server.hot.send({
                type: 'full-reload'
            });
        }
    },
}

export default defineConfig({
    base: mode === 'production' ? `/wp-content/themes/lrgp/dist/` : '/',
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
                purgecss({
                    content: [
                        './**/*.php',
                    ],
                    safelist: {
                        standard: [

                        ]
                    },
                })
            ],
        },
    },
    server: {
        port: 1337,
        host: '0.0.0.0',
        watch: {
            disableGlobbing: false,
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
});
