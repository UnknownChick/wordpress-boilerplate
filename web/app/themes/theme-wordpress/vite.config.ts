import { defineConfig } from 'vite';
import autoprefixer from 'autoprefixer';
import { browserslistToTargets } from 'lightningcss';
import browserslist from 'browserslist';

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
	base: `/app/themes/${process.env.THEME_FOLDER_NAME}/dist/`,
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
		cssMinify: 'lightningcss',
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
		},
	},
});
