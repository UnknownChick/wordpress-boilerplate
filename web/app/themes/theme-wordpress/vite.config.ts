import { defineConfig, Plugin } from 'vite';
import autoprefixer from 'autoprefixer';
import { babel } from '@rollup/plugin-babel';
import { promises as fsPromises } from 'fs';
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

	await fsPromises.mkdir(dirname(absolutePathToDestination), {
		recursive: true,
	  });
	  
	  await fsPromises.copyFile(sourcePath, absolutePathToDestination);
	},
});

const path = dirname(new URL(import.meta.url).pathname);
const __dirname = process.platform === 'win32' ? path.slice(1) : path;

export default defineConfig({
	base: '/wp-content/themes/theme-wordpress/dist/',
	plugins: [
		CopyFile({
			sourceFileName: 'app.ts',
			absolutePathToDestination: resolvePath(__dirname, './dist/app.js'),
		}),
		{
			name: 'php',
			handleHotUpdate({ file, server }) {
			  if (file.endsWith('.php')) {
				server.ws.send({ type: 'full-reload', path: '*'});
			  }
			},
		  },
	  ],
	build: {
		rollupOptions: {
			input: {
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