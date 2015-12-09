<?php
/**
 * This source file is subject to the (Open Source Initiative) BSD license
 * that is bundled with this package in the LICENSE file. It is also available
 * through the world-wide-web at this URL: http://www.ontic.com.au/license.html
 * If you did not receive a copy of the license and are unable to obtain it through
 * the world-wide-web, please send an email to license@ontic.com.au immediately.
 * Copyright (c) 2010-2015 Ontic. (http://www.ontic.com.au). All rights reserved.
 */

class Bootstrap
{
	/**
	 * Bootstrap the tests.
	 * 
	 * @return void
	 */
	public static function init()
	{
		// Module root directory path.
		$modulePath = dirname(__DIR__);
		// Module tests directory path.
		$testPath = __DIR__;
		// Application configuration.
		$configuration = [];
		
		// If a developer configuration file exists.
		if (is_file($configurationFile = $testPath.'/Configuration.php'))
		{
			// Retrieve the developer configuration.
			$configuration = include $configurationFile;
		}
		// If a distributed configuration file exists.
		else if (is_file($configurationFile = $testPath.'/Configuration.php.dist'))
		{
			// Retrieve the distributed configuration.
			$configuration = include $configurationFile;
		}
		else
		{
			// We cannot continue without a configuration file.
			throw new RuntimeException('Unable to locate a configuration file.');
		}
		
		// If a module autoload file exists.
		if (is_file($autoloadFile = $modulePath.'/vendor/autoload.php'))
		{
			// Retrieve the module autoloader
			$loader = include $autoloadFile;
		}
		// If a application autoload file exists.
		else if (is_file($autoloadFile = $modulePath.'/../../vendor/autoload.php'))
		{
			// Retrieve the application autoloader.
			$loader = include $autoloadFile;
		}
		else
		{
			// We cannot continue without a composer autoload file.
			throw new RuntimeException('Unable to locate a composer autoload file.');
		}
		
		// If additional autoload options are defined.
		if (isset($configuration['autoload']))
		{
			// Define the additional autoload types and corresponding methods.
			$autoloadTypes = ['psr-0' => 'add', 'psr-4' => 'addPsr4'];
			
			// Iterate over each of the autoload types.
			foreach ($autoloadTypes as $type => $method)
			{
				// If the autoload type is defined in the application configuration.
				if (isset($configuration['autoload'][$type]))
				{
					// Iterate over each of the autoload type options.
					foreach ($configuration['autoload'][$type] as $name => $paths)
					{
						// Cast the path to an array encase a single path is given.
						$paths = (array) $paths;
						
						// Iterate over each of the paths.
						foreach ($paths as $index => $path)
						{
							// Set the path as the parent directory's absolute path.
							$paths[$index] = dirname($path);
						}
						
						// Register a set of paths for a given name.
						$loader->{$method}($name, $paths);
					}
				}
			}
		}
		
		// If we are unable to locate the Zend MVC Application class.
		if (!class_exists('Zend\Mvc\Application'))
		{
			// We cannot continue without the dependencies being loaded.
			throw new RuntimeException('Unable to load the Zend framework. Install dependencies using Composer.');
		}
		
		// Create a service manager config object.
		$serviceManagerConfig = new Zend\Mvc\Service\ServiceManagerConfig();
		// Create a server manager object and inject the configuration.
		$serviceManager = new Zend\ServiceManager\ServiceManager($serviceManagerConfig);
		// Set the configuration used when initializing the application.
		$serviceManager->setService('ApplicationConfig', $configuration);
		// Retrieve the module manager.
		$moduleManager = $serviceManager->get('ModuleManager');
		// Load available modules.
		$moduleManager->loadModules();
		
		// If a test case class is defined.
		if (isset($configuration['module_test_case_class']))
		{
			// Retrieve the test case class.
			$moduleTestCaseClass = $configuration['module_test_case_class'];
			
			// If the test case class has a method named setServiceManager.
			if (method_exists($moduleTestCaseClass, 'setConfiguration'))
			{
				// Pass the configuration to the test case class.
				call_user_func_array($moduleTestCaseClass.'::setConfiguration', array($configuration));
			}
			
			// If the test case class has a method named setServiceManager.
			if (method_exists($moduleTestCaseClass, 'setServiceManager'))
			{
				// Pass an instance of the service manager to the test case class.
				call_user_func_array($moduleTestCaseClass.'::setServiceManager', array($serviceManager));
			}
		}
	}
}

// Bootstrap the tests
Bootstrap::init();