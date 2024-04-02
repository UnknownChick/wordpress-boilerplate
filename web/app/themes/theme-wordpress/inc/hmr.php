<?php defined('ABSPATH') || die();

/**
 * Retrieves the address of the Vite development server.
 *
 * @return string The address of the Vite development server.
 */
function getViteDevServerAddress(): string
{
    return VITE_DEV_SERVER;
}

/**
 * Checks if the Vite Hot Module Replacement (HMR) is available.
 *
 * @return bool True if the Vite HMR is available, false otherwise.
 */
function isViteHMRAvailable(): bool
{
    return !empty(getViteDevServerAddress());
}

/**
 * Loads a given JavaScript script as an ECMAScript module.
 *
 * This function uses the WordPress 'script_loader_tag' filter to modify the script tag of the
 * specified script, changing its type to 'module'. This allows the script to be loaded as an
 * ECMAScript module, which can import other modules using the 'import' keyword.
 *
 * @param string $script_handle The handle of the script to load as a module. This handle should
 * correspond to a script that has been registered using wp_register_script() or wp_enqueue_script().
 *
 * @return void
 */
function loadJSScriptAsESModule(string $script_handle): void
{
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
