<?php
require __DIR__ . '/../src/Zap/Injector.php';
require __DIR__ . '/Injector/DummyFactory.php';
require __DIR__ . '/Injector/DummyClass.php';

function globalFunction(\Closure $closure) {
	return $closure;
}
