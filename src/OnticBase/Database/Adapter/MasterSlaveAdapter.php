<?php
/**
 * This source file is subject to the (Open Source Initiative) BSD license
 * that is bundled with this package in the LICENSE file. It is also available
 * through the world-wide-web at this URL: http://www.ontic.com.au/license.html
 * If you did not receive a copy of the license and are unable to obtain it through
 * the world-wide-web, please send an email to license@ontic.com.au immediately.
 * Copyright (c) 2010-2015 Ontic. (http://www.ontic.com.au). All rights reserved.
 */

namespace OnticBase\Database\Adapter;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\DriverInterface;
use Zend\Db\Adapter\Platform\PlatformInterface;
use Zend\Db\Adapter\Profiler\ProfilerInterface;
use Zend\Db\ResultSet\ResultSetInterface;

class MasterSlaveAdapter extends Adapter implements MasterSlaveAdapterInterface
{
	/**
	 * Slave database adapter.
	 * 
	 * @var AdapterInterface
	 */
	protected $slaveAdapter;
	
	/**
	 * Class constructor.
	 * 
	 * @param AdapterInterface $slaveAdapter
	 * @param DriverInterface|array $driver
	 * @param PlatformInterface $platform
	 * @param ResultSetInterface $queryResultPrototype
	 * @param ProfilerInterface $profiler
	 * @return MasterSlaveAdapter
	 */
	public function __construct(AdapterInterface $slaveAdapter, $driver, PlatformInterface $platform = null, ResultSetInterface $queryResultPrototype = null, ProfilerInterface $profiler = null)
	{
		$this->slaveAdapter = $slaveAdapter;
		
		parent::__construct($driver, $platform, $queryResultPrototype, $profiler);
	}
	
	/**
	 * Retrieve the slave database adapter.
	 * 
	 * @return AdapterInterface
	 */
	public function getSlaveAdapter()
	{
		return $this->slaveAdapter;
	}
}