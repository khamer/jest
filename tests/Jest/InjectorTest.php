<?php

/*
 * This file is part of the Jest package.
 *
 * (c) Jeff Turcotte <jeff.turcotte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jest;

class InjectorTest extends \PHPUnit_Framework_TestCase
{
	public function testSetValidOffset()
	{
		$injector = new Injector();

		$injector->addFactory('Closure', function() {
			return new Injector();
		});

		$injector->addFactory('CallableObject', new DummyFactory());

		$this->assertInstanceOf('Jest\\Injector', $injector->get('Closure'));
		$this->assertInstanceOf('Jest\\Injector', $injector->get('CallableObject'));
	}


	/**
     * @expectedException \InvalidArgumentException
	 */
	public function testSetInvalidOffset()
	{
		$injector = new Injector();
		$injector->addFactory('Injector', 'Not Allowed');
	}


	/**
     * @expectedException \InvalidArgumentException
	 */
	public function testGetInvalidOffset()
	{
		$injector = new Injector();
		$injector->get('Invalid');
	}


	public function testIsset()
	{
		$injector = new Injector();
		$injector->addFactory('Test', function() {});

		$this->assertEquals(true, $injector->has('Test'));
	}


	public function testUnset()
	{
		$injector = new Injector();
		$injector->addFactory('Test', function() {});

		$injector->remove('Test');

		$this->assertEquals(false, $injector->has('Test'));
	}


	public function testInvoke()
	{
		$test = $this;

		$injector = new Injector();
		$injector->addFactory('Jest\Injector', new DummyFactory());
		$injector->addFactory('Closure', function () { return function() {}; });

		$injector->invoke(
			function(\Closure $func, Injector $injector) use ($test) {
				$test->assertInstanceOf('Closure', $func);
				$test->assertInstanceOf('Jest\\Injector', $injector);
			}
		);

		$dummyClass = new DummyClass(function(){});

		$this->assertInstanceOf('Closure', $injector->invoke(array($dummyClass, 'method')));
		$this->assertInstanceOf('Closure', $injector->invoke('globalFunction'));
		$this->assertInstanceOf('Closure', $injector->invoke('\Jest\DummyClass::staticMethod'));
		$this->assertInstanceOf('Closure', $injector->invoke(array('\Jest\DummyClass', 'staticMethod')));
	}

	public function testCreate()
	{
		$injector = new Injector();
		$injector->addFactory('Closure', function () { return function() {}; });

		$this->assertInstanceOf('\Jest\DummyClass', $injector->create('\Jest\DummyClass'));
	}

	public function testAddInstance()
	{
		$test = $this;

		$injector = new Injector();
		$injector->addInstance(function () { return function() {}; });

		$injector->invoke(
			function(\Closure $func) use ($test) {
				$test->assertInstanceOf('Closure', $func);
			}
		);
	}

	public function testAddClass()
	{
		$injector = new Injector();
		$injector->addClass('Jest\DummyClass');

		$injector->addInstance(function () { return function() {}; });

		$injector->invoke(function(\Jest\DummyClass $dummy) {
			$this->assertInstanceOf('\Jest\DummyClass', $dummy);
		});
	}

	/**
     * @expectedException \LogicException
	 */
	public function testInvalidDependency()
	{
		$injector = new Injector();

		$injector->addFactory('Closure', function() {
			return new Injector();
		});

		$injector->addFactory('Jest\Injector', function(Injector $injector) {});

		$injector->invoke(function(Injector $injector) {});
	}
}
