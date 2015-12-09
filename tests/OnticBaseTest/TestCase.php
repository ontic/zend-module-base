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

use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;

class TestCase extends PHPUnit_Framework_TestCase
{
	/**
	 * Application configuration.
	 * 
	 * @var array
	 */
	protected static $configuration;
	
	/**
	 * Service manager.
	 * 
	 * @var ServiceManager
	 */
	protected static $serviceManager;
	
	/**
	 * Retrieve the application configuration.
	 * 
	 * @return array
	 */
	public static function getConfiguration()
	{
		return static::$configuration;
	}
	
	/**
	 * Set the application configuration.
	 * 
	 * @param array $configuration
	 * @return void
	 */
	public static function setConfiguration(array $configuration)
	{
		static::$configuration = $configuration;
	}
	
	/**
	 * Retrieve the service manager.
	 * 
	 * @return ServiceManager
	 */
	public static function getServiceManager()
	{
		return static::$serviceManager;
	}
	
	/**
	 * Set the service manager.
	 * 
	 * @param ServiceManager $serviceManager
	 * @return void
	 */
	public static function setServiceManager(ServiceManager $serviceManager)
	{
		static::$serviceManager = $serviceManager;
	}
}