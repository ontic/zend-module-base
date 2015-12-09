<?php
/**
 * This source file is subject to the (Open Source Initiative) BSD license
 * that is bundled with this package in the LICENSE file. It is also available
 * through the world-wide-web at this URL: http://www.ontic.com.au/license.html
 * If you did not receive a copy of the license and are unable to obtain it through
 * the world-wide-web, please send an email to license@ontic.com.au immediately.
 * Copyright (c) 2010-2015 Ontic. (http://www.ontic.com.au). All rights reserved.
 */

namespace OnticBaseTest;

use ReflectionClass;
use OnticBase\Module;
use Zend\Loader\AutoloaderFactory;

class ModuleTest extends TestCase
{
	/**
	 * Module instance.
	 * 
	 * @var Module
	 */
	protected $module;
	
	public function setUp()
	{
		$this->module = new Module;
	}
	
	public function testModuleImplementsInterfaces()
	{
		$this->assertInstanceOf('Zend\ModuleManager\Feature\AutoloaderProviderInterface', $this->module);
		$this->assertInstanceOf('Zend\ModuleManager\Feature\ConfigProviderInterface', $this->module);
		$this->assertInstanceOf('Zend\ModuleManager\Feature\LocatorRegisteredInterface', $this->module);
	}
	
	public function testGetDir()
	{
		$this->assertInternalType('string', $this->module->getDir());
		$this->assertEquals(dirname(dirname(__DIR__)), $this->module->getDir());
	}
	
	public function testGetNamespace()
	{
		$reflector = new ReflectionClass($this->module);
		$this->assertInternalType('string', $this->module->getNamespace());
		$this->assertEquals($reflector->getNamespaceName(), $this->module->getNamespace());
	}
	
	public function testGetConfig()
	{
		$this->assertInternalType('array', $this->module->getConfig());
	}
	
	public function testGetAutoloaderConfig()
	{
		$this->assertInternalType('array', $this->module->getAutoloaderConfig());
		$this->assertArrayHasKey(AutoloaderFactory::STANDARD_AUTOLOADER, $this->module->getAutoloaderConfig());
	}
}