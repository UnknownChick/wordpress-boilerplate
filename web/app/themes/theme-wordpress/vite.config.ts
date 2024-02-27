import { defineConfig, Plugin } from 'vite';
import autoprefixer from 'autoprefixer';
import { babel } from '@rollup/plugin-babel';
import fs from 'fs';
import { resolve as resolvePath, dirname } from 'path';

const CopyFile = ({
	sourceFileName,
	absolutePathToDestination,
}: {
	sourceFileName: string;
	absolutePathToDestination: string;
}): Plugin => ({
	name: 'copy-file-plugin',
	writeBundle: async (options, bundle) => {
	const fileToCopy = Object.values(bundle).find(({ name }) => name === sourceFileName);

	if (!fileToCopy) {
		return;
	}

	const sourcePath = resolvePath(options.dir, fileToCopy.fileName);

	await fs.promises.mkdir(dirname(absolutePathToDestination), {
		recursive: true,
	});

	await fs.promises.copyFile(sourcePath, absolutePathToDestination);
	},
});

const path = new URL('.', import.meta.url).pathname;
const __dirname = process.platform === 'win32' ? path.slice(1) : path;

export default defineConfig({
	base: '/wp-content/themes/theme-wordpress/dist/',
	plugins: [
		CopyFile({
		  sourceFileName: 'style.css',
		  absolutePathToDestination: resolvePath(__dirname, './style.css'),
		}),
		CopyFile({
			sourceFileName: 'app.ts',
			absolutePathToDestination: resolvePath(__dirname, './dist/app.js'),
		  }),
	  ],
	build: {
		rollupOptions: {
			input: {
				main: './assets/sass/style.ts',
				style: './assets/sass/style.scss',
				app: './assets/ts/app.ts'
			},
			output: {
				dir: 'dist',
				format: 'es',
				entryFileNames: `[name].js`,
				chunkFileNames: `[name].js`,
				assetFileNames: `[name].[ext]`
			},
			plugins: [
				babel({
					babelHelpers: 'bundled',
					extensions: ['.js', '.ts']
				})
			]
		}
	},
	css: {
		postcss: {
			plugins: [
				autoprefixer({})
			],
		}
	},
	server: {
		port: 1337,
		host: '0.0.0.0',
	},
});