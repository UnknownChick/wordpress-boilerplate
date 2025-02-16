<?php

namespace App\Utils;

use Exception;

/**
 * Class FileAutoload
 * @package App\Utils
 */
class FileAutoload
{
    private static ?FileAutoload $instance = null;
    private array $files = array();

    private function __construct()
    {
    }

    /**
     * Singleton
     *
     * @return FileAutoload|null
     */
    public static function getInstance(): ?FileAutoload
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $subdir
     * @param $files
     * @return void
     */
    public function addFiles($subdir, $files): void
    {
        $subdir = trim($subdir, '/');

        foreach ($files as $file) {
            if (!empty($subdir)) {
                $this->files[] = '/inc/' . $subdir . '/' . $file;
            } else {
                $this->files[] = '/inc/' . $file;
            }
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function load(): void
    {
        foreach ($this->files as $file) {
            $filepath = locate_template($file);

            if ($filepath && file_exists($filepath)) {
                require_once $filepath;
            } else {
                trigger_error("Error locating `$file` for inclusion!", E_USER_ERROR);
            }
        }
        unset($file, $filepath);
    }
}
