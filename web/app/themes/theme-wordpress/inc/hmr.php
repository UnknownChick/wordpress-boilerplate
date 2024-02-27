<?php
function getViteDevServerAddress()
{
	return getenv('VITE_DEV_SERVER_URL');
}

function isViteHMRAvailable()
{
	return !empty(getViteDevServerAddress());
}

/**
 * Loads given script as a EcmaScript module.
 * 
 * @param string $script_handle Name of the script to load as
 * module.
 *
 * @return void
 */
function loadJSScriptAsESModule($script_handle)
{
	add_filter(
		'script_loader_tag',
		function ($tag, $handle, $src) use ($script_handle) {
			if ($script_handle === $handle) {
				return sprintf(
					'<script type="module" src="%s"></script>',
					esc_url($src)
				);
			}
			return $tag;
		}, 10, 3
	);
}

function enqueue_vite_client() {
	$VITE_HMR_CLIENT_HANDLE = 'vite-client';
	if (isViteHMRAvailable()) {
		wp_enqueue_script(
			$VITE_HMR_CLIENT_HANDLE,
			getViteDevServerAddress().'/@vite/client',
			array(),
			null
		);
		loadJSScriptAsESModule($VITE_HMR_CLIENT_HANDLE);
	}
}

add_action('wp_enqueue_scripts', 'enqueue_vite_client');

if (isViteHMRAvailable()) {
	add_filter(
		'stylesheet_uri', function () {
			return getViteDevServerAddress().'/assets/sass/style.scss';
		}
	);

	if(WP_ENV == 'development') {
		add_filter(
			'stylesheet_directory_uri', function () {
				return getViteDevServerAddress().'/assets/sass';
			}
		);
	}
	
}
?>