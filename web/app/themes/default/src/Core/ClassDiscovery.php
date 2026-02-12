<?php

namespace Theme\Core;

defined('ABSPATH') || die();

use ReflectionClass;
use DirectoryIterator;

class ClassDiscovery
{
	/**
	 * Returns all classes from a directory that implement a given interface
	 *
	 * @param string $directory  Absolute path to the directory to scan
	 * @param string $namespace  Corresponding namespace (e.g. "Theme\PostTypes")
	 * @param string $interface  Interface that the classes must implement
	 *
	 * @return string[]          List of found fully qualified class names (FQCN)
	 */
	public static function find(string $directory, string $namespace, string $interface): array
	{
		if (!is_dir($directory)) {
			return [];
		}

		$classes = [];

		foreach (new DirectoryIterator($directory) as $file) {
			if (!$file->isFile() || $file->getExtension() !== 'php') {
				continue;
			}

			$class = $namespace . '\\' . $file->getBasename('.php');

			if (!class_exists($class)) {
				continue;
			}

			$reflection = new ReflectionClass($class);

			if ($reflection->isAbstract() || $reflection->isInterface()) {
				continue;
			}

			if (!$reflection->implementsInterface($interface)) {
				continue;
			}

			$classes[] = $class;
		}

		return $classes;
	}
}
