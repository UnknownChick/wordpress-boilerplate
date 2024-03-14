<?php
function getViteDevServerAddress() {
	return VITE_DEV_SERVER;
}

function isViteHMRAvailable() {
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
function loadJSScriptAsESModule($script_handle) {
	add_filter('script_loader_tag', function ($tag, $handle, $src) use ($script_handle) {
		if ($script_handle === $handle) {
			return sprintf(
				'<script type="module" src="%s"></script>',
				esc_url($src)
			);
		}
		return $tag;
	}, 10, 3);
}