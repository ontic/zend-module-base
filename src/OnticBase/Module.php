<?php
/**
 * This source file is subject to the (Open Source Initiative) BSD license
 * that is bundled with this package in the LICENSE file. It is also available
 * through the world-wide-web at this URL: http://www.ontic.com.au/license.html
 * If you did not receive a copy of the license and are unable to obtain it through
 * the world-wide-web, please send an email to license@ontic.com.au immediately.
 * Copyright (c) 2010-2015 Ontic. (http://www.ontic.com.au). All rights reserved.
 */

namespace OnticBase;

use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\LocatorRegisteredInterface;

class Module implements
	AutoloaderProviderInterface,
	ConfigProviderInterface,
	LocatorRegisteredInterface
{
	/**
	 * Retrieve the module directory.
	 * 
	 * @return string
	 */
	public function getDir()
	{
		return dirname(dirname(__DIR__));
	}
	
	/**
	 * Retrieve the module namespace.
	 * 
	 * @return string
	 */
	public function getNamespace()
	{
		return __NAMESPACE__;
	}
	
	/**
	 * Retrieve the module configuration to merge with the application configuration.
	 * 
	 * @return array
	 */
	public function getConfig()
	{
		return include $this->getDir().'/config/module.config.php';
	}
	
	/**
	 * Retrieve the module configuration to merge with the autoloader factory.
	 * 
	 * @return array
	 */
	public function getAutoloaderConfig()
	{
		return [
			AutoloaderFactory::STANDARD_AUTOLOADER => [
				StandardAutoloader::LOAD_NS => [
					$this->getNamespace() => $this->getDir().'/src/'.$this->getNamespace()
				]
			]
		];
	}
}