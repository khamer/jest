<?php
namespace Dummies;

class DummyClass
{
	public function __construct(\Closure $closure) {
		$this->closure = $closure;
	}

	public function method(\Closure $closure) {
		return $closure;
	}

	static public function staticMethod(\Closure $closure) {
		return $closure;
	}
}

