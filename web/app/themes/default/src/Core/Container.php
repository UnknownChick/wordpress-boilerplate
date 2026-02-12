<?php

namespace Theme\Core;

defined('ABSPATH') || die();

use LogicException;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;

class Container
{
	private static ?self $instance = null;
	private array $bindings   = [];
	private array $singletons = [];

	private function __construct() {}

	/**
	 * Returns the singleton instance of the container
	 */
	public static function getInstance(): static
	{
		if (static::$instance === null) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Registers a binding that returns a new instance on each resolution
	 *
	 * @param string $abstract  The abstract type or identifier
	 * @param callable|string $concrete  The concrete implementation or factory
	 */
	public function bind(string $abstract, callable|string $concrete): void
	{
		$this->bindings[$abstract] = $concrete;
	}

	/**
	 * Registers a shared (singleton) binding
	 *
	 * @param string $abstract  The abstract type or identifier
	 * @param callable|string $concrete  The concrete implementation or factory
	 */
	public function singleton(string $abstract, callable|string $concrete): void
	{
		$this->bindings[$abstract] = $concrete;
		$this->singletons[$abstract] = null;
	}

	/**
	 * Resolves and returns an instance for the given abstract
	 *
	 * @param string $abstract  The abstract type or class name
	 *
	 * @return object
	 */
	public function get(string $abstract): object
	{
		if (array_key_exists($abstract, $this->singletons) && $this->singletons[$abstract] !== null) {
			return $this->singletons[$abstract];
		}

		if (isset($this->bindings[$abstract])) {
			$concrete = $this->bindings[$abstract];
			$instance = is_callable($concrete)
				? $concrete($this)
				: $this->build($concrete);

			if (array_key_exists($abstract, $this->singletons)) {
				$this->singletons[$abstract] = $instance;
			}

			return $instance;
		}

		return $this->build($abstract);
	}

	/**
	 * Builds a class instance by automatically injecting its dependencies
	 *
	 * @param string $className  Fully qualified class name
	 *
	 * @return object
	 */
	private function build(string $className): object
	{
		$reflection = new ReflectionClass($className);

		if (!$reflection->isInstantiable()) {
			throw new LogicException("Class [{$className}] is not instantiable.");
		}

		$constructor = $reflection->getConstructor();

		if ($constructor === null) {
			return new $className();
		}

		$dependencies = array_map(function (ReflectionParameter $param) use ($className) {
			$type = $param->getType();

			if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
				return $this->get($type->getName());
			}

			if ($param->isDefaultValueAvailable()) {
				return $param->getDefaultValue();
			}

			throw new LogicException(
				"Impossible to resolve the parameter [\${$param->getName()}] of [{$className}]."
			);
		}, $constructor->getParameters());

		return $reflection->newInstanceArgs($dependencies);
	}
}
