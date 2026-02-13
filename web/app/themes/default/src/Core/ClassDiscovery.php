<?php

namespace Theme\Core;

defined('ABSPATH') || die();

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use Theme\Attributes\Condition;
use Theme\Attributes\OnHook;
use Theme\Contracts\Registerable;

class ClassDiscovery
{
	/**
	 * Recursively scan a directory for PHP classes implementing Registerable
	 *
	 * @param string $directory The base directory to scan
	 * @param string $namespace The base namespace prefix (e.g. "Theme")
	 *
	 * @return array<int, class-string<Registerable>>
	 */
	public static function find(string $directory, string $namespace): array
	{
		if (!is_dir($directory)) {
			return [];
		}

		$classes = [];

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ($iterator as $file) {
			if (!$file->isFile() || $file->getExtension() !== 'php') {
				continue;
			}

			$relativeFile = substr($file->getPathname(), strlen($directory) + 1);
			$className = str_replace(['.php', DIRECTORY_SEPARATOR], ['', '\\'], $relativeFile);
			$fqcn = $namespace . '\\' . $className;

			if (!class_exists($fqcn)) {
				continue;
			}

			$reflection = new ReflectionClass($fqcn);

			if ($reflection->isAbstract() || $reflection->isInterface()) {
				continue;
			}

			if (!$reflection->implementsInterface(Registerable::class)) {
				continue;
			}

			$classes[] = $fqcn;
		}

		return $classes;
	}

	/**
	 * Boot a Registerable class respecting its #[Condition] and #[OnHook] attributes
	 *
	 * @param string $class The fully qualified class name
	 * @param Registerable $instance An instance of the class
	 *
	 * @return void
	 */
	public static function boot(string $class, Registerable $instance): void
	{
		$reflection = new ReflectionClass($class);

		foreach ($reflection->getAttributes(Condition::class) as $attr) {
			/** @var Condition $condition */
			$condition = $attr->newInstance();
			if (!$condition->evaluate()) {
				return;
			}
		}

		$hook     = 'init';
		$priority = 10;

		$hookAttrs = $reflection->getAttributes(OnHook::class);
		if (!empty($hookAttrs)) {
			/** @var OnHook $onHook */
			$onHook   = $hookAttrs[0]->newInstance();
			$hook     = $onHook->hook;
			$priority = $onHook->priority;
		}

		add_action($hook, [$instance, 'register'], $priority);
	}
}
