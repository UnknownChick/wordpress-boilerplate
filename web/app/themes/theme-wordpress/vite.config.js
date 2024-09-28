import { defineConfig } from 'vite';
import autoprefixer from 'autoprefixer';
import { browserslistToTargets } from 'lightningcss';
import browserslist from 'browserslist';
import path from 'path';

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
    // base: `/app/themes/${process.env.THEME_FOLDER_NAME}/dist/`,
    publicDir: '',
    plugins: [phpRefreshPlugin],
    css: {
        transformer: 'postcss',
        lightningcss: {
            targets: browserslistToTargets(browserslist('>= 0.25%'))
        },
        postcss: {
            plugins: [
                autoprefixer({}),
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
