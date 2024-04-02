import { defineConfig } from 'vite';
import autoprefixer from 'autoprefixer';
import { babel } from '@rollup/plugin-babel';

export default defineConfig({
	base: '/app/themes/' + process.env.THEME_FOLDER_NAME + '/dist/',
	plugins: [
		{
			name: 'php',
			handleHotUpdate({ file, server }) {
				if (file.endsWith('.php')) {
					server.ws.send({ type: 'full-reload', path: '*' });
				}
			},
		},
	],
	build: {
		rollupOptions: {
			input: {
				style: './assets/sass/style.scss',
				app: './assets/ts/app.ts',
			},
			output: {
				dir: 'dist',
				format: 'es',
				entryFileNames: `[name].js`,
				chunkFileNames: `[name].js`,
				assetFileNames: `[name].[ext]`,
			},
			plugins: [
				babel({
					babelHelpers: 'bundled',
					extensions: ['.js', '.ts'],
				}),
			],
		},
	},
	css: {
		postcss: {
			plugins: [
				autoprefixer({}),
			],
		},
	},
	server: {
		port: 1337,
		host: '0.0.0.0',
	},
});
