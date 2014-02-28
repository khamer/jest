<?php

namespace Dummies;

use iMarc\Zap\Injector;

class DummyFactory
{
	function __invoke() {
		return new Injector();
	}
}

