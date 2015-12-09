<?php
/**
 * This source file is subject to the (Open Source Initiative) BSD license
 * that is bundled with this package in the LICENSE file. It is also available
 * through the world-wide-web at this URL: http://www.ontic.com.au/license.html
 * If you did not receive a copy of the license and are unable to obtain it through
 * the world-wide-web, please send an email to license@ontic.com.au immediately.
 * Copyright (c) 2010-2015 Ontic. (http://www.ontic.com.au). All rights reserved.
 */

namespace OnticBaseTest\Mapper;

use stdClass;
use OnticBase\Database\Adapter\MasterSlaveAdapter;
use OnticBase\Database\Mapper\AbstractMapper;
use OnticBaseTest\TestCase;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ConnectionInterface;
use Zend\Db\Adapter\Driver\DriverInterface;
use Zend\Db\Adapter\Driver\StatementInterface;
use Zend\Db\Adapter\Platform\PlatformInterface;
use Zend\Hydrator\ObjectProperty;

class AbstractDatabaseMapperTest extends TestCase
{
	/**
	 * Driver instance.
	 * 
	 * @var DriverInterface
	 */
	protected $driver;
	
	/**
	 * Connection instance.
	 * 
	 * @var ConnectionInterface
	 */
	protected $connection;
	
	/**
	 * Statement instance.
	 * 
	 * @var StatementInterface
	 */
	protected $statement;
	
	/**
	 * Platform instance.
	 * 
	 * @var PlatformInterface
	 */
	protected $platform;
	
	/**
	 * Mapper instance.
	 *
	 * @var AbstractMapper
	 */
	protected $mapper;
	
	public function setUp()
	{
		$this->driver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
		$this->connection = $this->getMock('Zend\Db\Adapter\Driver\ConnectionInterface');
		$this->statement = $this->getMock('Zend\Db\Adapter\Driver\StatementInterface');
		$this->platform = $this->getMock('Zend\Db\Adapter\Platform\PlatformInterface');
		$this->mapper = $this->getMockForAbstractClass('OnticBase\Database\Mapper\AbstractMapper');
		$this->driver->expects($this->any())->method('checkEnvironment')->will($this->returnValue(true));
		$this->driver->expects($this->any())->method('getConnection')->will($this->returnValue($this->connection));
		$this->driver->expects($this->any())->method('createStatement')->will($this->returnValue($this->statement));
	}
	
	public function testGetAndSetPrototype()
	{
		$prototype = new stdClass();
		$this->assertNull($this->mapper->getPrototype());
		$this->mapper->setPrototype($prototype);
		$this->assertInstanceOf('stdClass', $this->mapper->getPrototype());
		$this->assertSame($prototype, $this->mapper->getPrototype());
	}
	
	public function testGetAndSetHydrator()
	{
		$hydrator = new ObjectProperty();
		$this->assertNotNull($this->mapper->getHydrator());
		$this->mapper->setHydrator($hydrator);
		$this->assertInstanceOf('Zend\Hydrator\ObjectProperty', $this->mapper->getHydrator());
		$this->assertSame($hydrator, $this->mapper->getHydrator());
	}
	
	public function testMasterAndSlaveAdapter()
	{
		$slaveAdapter = new Adapter($this->driver, $this->platform);
		$masterSlaveAdapter = new MasterSlaveAdapter($slaveAdapter, $this->driver, $this->platform);
		$this->mapper->setAdapter($masterSlaveAdapter);
		$this->assertSame($masterSlaveAdapter, $this->mapper->getAdapter());
		$this->assertSame($masterSlaveAdapter->getSlaveAdapter(), $this->mapper->getSlaveAdapter());
		$this->assertSame($slaveAdapter, $this->mapper->getSlaveAdapter());
	}
	
	public function testGetSlaveIsMasterWhenOmitted()
	{
		$adapter = new Adapter($this->driver, $this->platform);
		$this->mapper->setAdapter($adapter);
		$this->assertSame($this->mapper->getSlaveAdapter(), $this->mapper->getAdapter());
	}
	
	public function testGetAndSetSlaveAdapter()
	{
		$slaveAdapter = new Adapter($this->driver, $this->platform);
		$this->assertNull($this->mapper->getSlaveAdapter());
		$this->mapper->setSlaveAdapter($slaveAdapter);
		$this->assertSame($slaveAdapter, $this->mapper->getSlaveAdapter());
	}
	
	public function testGetAndSetAdapter()
	{
		$adapter = new Adapter($this->driver, $this->platform);
		$this->assertNull($this->mapper->getAdapter());
		$this->mapper->setAdapter($adapter);
		$this->assertSame($adapter, $this->mapper->getAdapter());
	}
}