<?php
require __DIR__ . '/../src/Injector.php';
require __DIR__ . '/Injector/DummyFactory.php';
require __DIR__ . '/Injector/DummyClass.php';

function globalFunction(\Closure $closure) {
	return $closure;
}
