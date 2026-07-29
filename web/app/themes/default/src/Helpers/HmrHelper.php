<?php
namespace Theme\Helpers;

defined('ABSPATH') || die();

use function Env\env;

/**
 * Class HMR
 *
 * Handles Hot Module Replacement (HMR) functionality for Vite integration.
 */
class HmrHelper {
    private string $viteDevServer;

    /**
     * HMR constructor.
     */
    public function __construct() {
        $this->viteDevServer = env('VITE_DEV_SERVER') ?: '';
    }

    /**
     * Retrieves the address of the Vite development server.
     *
     * @return string The address of the Vite development server.
     */
    public function getViteDevServerAddress(): string
    {
        return $this->viteDevServer;
    }

    /**
     * Checks if the Vite Hot Module Replacement (HMR) is available.
     *
     * @return bool True if the Vite HMR is available, false otherwise.
     */
    public function isHMRAvailable(): bool
    {
        return !empty($this->viteDevServer);
    }
}
