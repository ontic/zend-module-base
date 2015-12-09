<?php
/**
 * This source file is subject to the (Open Source Initiative) BSD license
 * that is bundled with this package in the LICENSE file. It is also available
 * through the world-wide-web at this URL: http://www.ontic.com.au/license.html
 * If you did not receive a copy of the license and are unable to obtain it through
 * the world-wide-web, please send an email to license@ontic.com.au immediately.
 * Copyright (c) 2010-2015 Ontic. (http://www.ontic.com.au). All rights reserved.
 */

namespace OnticBase\Database\Mapper;

use Closure;
use OnticBase\Database\Adapter\MasterSlaveAdapterInterface;
use OnticBase\Database\Mapper\Exception\InvalidArgumentException;
use OnticBase\Database\Mapper\Exception\RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Where;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;

abstract class AbstractMapper
{
	/**
	 * Database adapter.
	 * 
	 * @var AdapterInterface
	 */
	protected $adapter;
	
	/**
	 * Slave database adapter.
	 * 
	 * @var AdapterInterface
	 */
	protected $slaveAdapter;
	
	/**
	 * Result set hydrator.
	 * 
	 * @var HydratorInterface
	 */
	protected $hydrator;
	
	/**
	 * Prototype object.
	 * 
	 * @var object
	 */
	protected $prototype;
	
	/**
	 * Sql object.
	 * 
	 * @var Sql
	 */
	protected $sql;
	
	/**
	 * Slave Sql object.
	 *
	 * @var Sql
	 */
	protected $slaveSql;
	
	/**
	 * Table name.
	 * 
	 * @var string
	 */
	protected $tableName;
	
	/**
	 * Whether the mapper has been initialized.
	 * 
	 * @var boolean
	 */
	protected $initialized = false;
	
	/**
	 * Fetch results.
	 * 
	 * @param Select $select
	 * @param object|null $prototype
	 * @param HydratorInterface|null $hydrator
	 * @return HydratingResultSet
	 * @codeCoverageIgnore
	 */
	protected function select(Select $select, $prototype = null, HydratorInterface $hydrator = null)
	{
		if ($prototype === null)
		{
			$prototype = $this->getPrototype();
		}
		
		if ($hydrator === null)
		{
			$hydrator = $this->getHydrator();
		}
		
		$statement = $this->initialize()->getSlaveSql()->prepareStatementForSqlObject($select);
		$resultSet = new HydratingResultSet($hydrator, $prototype);
		
		return $resultSet->initialize($statement->execute());
	}
	
	/**
	 * Insert an entity.
	 * 
	 * @param array|object $entity
	 * @param null|string|array|TableIdentifier $table
	 * @param HydratorInterface $hydrator
	 * @return ResultInterface
	 * @codeCoverageIgnore
	 */
	protected function insert($entity, $table = null, HydratorInterface $hydrator = null)
	{
		if ($table === null)
		{
			$table = $this->getTableName();
		}
		
		if ($hydrator === null)
		{
			$hydrator = $this->getHydrator();
		}
		
		$insert = $this->initialize()->getSql()->setTable($table)->insert();
		$insert->values($this->entityToArray($entity, $hydrator));
		
		return $this->getSql()->prepareStatementForSqlObject($insert)->execute();
	}
	
	/**
	 * Update an entity.
	 * 
	 * @param array|object $entity
	 * @param Where|Closure|string|array $where
	 * @param null|string|array|TableIdentifier $table
	 * @param HydratorInterface $hydrator
	 * @return ResultInterface
	 * @codeCoverageIgnore
	 */
	protected function update($entity, $where, $table = null, HydratorInterface $hydrator = null)
	{
		if ($table === null)
		{
			$table = $this->getTableName();
		}
		
		if ($hydrator === null)
		{
			$hydrator = $this->getHydrator();
		}
		
		$update = $this->initialize()->getSql()->setTable($table)->update();
		$update->set($this->entityToArray($entity, $hydrator))->where($where);
		
		return $this->getSql()->prepareStatementForSqlObject($update)->execute();
	}
	
	/**
	 * Delete an entity.
	 * 
	 * @param string|array|Closure $where
	 * @param null|string|array|TableIdentifier $table
	 * @return ResultInterface
	 * @codeCoverageIgnore
	 */
	protected function delete($where, $table = null)
	{
		if ($table === null)
		{
			$table = $this->getTableName();
		}
		
		$delete = $this->initialize()->getSql()->setTable($table)->delete();
		$delete->where($where);
		
		return $this->getSql()->prepareStatementForSqlObject($delete)->execute();
	}
	
	/**
	 * Initialize the mapper before running queries.
	 * 
	 * @return AbstractMapper
	 * @throws RuntimeException
	 * @codeCoverageIgnore
	 */
	protected function initialize()
	{
		if ($this->initialized)
		{
			return $this;
		}
		
		if (!$this->getAdapter() instanceof AdapterInterface)
		{
			throw new RuntimeException('No database adapter is specified.');
		}
		
		if (!$this->getHydrator() instanceof HydratorInterface)
		{
			throw new RuntimeException('No hydrator is specified.');
		}
		
		if (!is_object($this->getPrototype()))
		{
			throw new RuntimeException('No prototype object is specified.');
		}
		
		$this->initialized = true;
		
		return $this;
	}
	
	/**
	 * Retrieve the database adapter.
	 * 
	 * @return AdapterInterface
	 */
	public function getAdapter()
	{
		return $this->adapter;
	}
	
	/**
	 * Set the database adapter.
	 * 
	 * @param AdapterInterface $adapter
	 * @return AbstractMapper
	 */
	public function setAdapter(AdapterInterface $adapter)
	{
		$this->adapter = $adapter;
		
		if ($adapter instanceof MasterSlaveAdapterInterface)
		{
			$this->slaveAdapter = $adapter->getSlaveAdapter();
		}
		
		return $this;
	}
	
	/**
	 * Retrieve the slave database adapter.
	 * 
	 * @return AdapterInterface
	 */
	public function getSlaveAdapter()
	{
		if ($this->slaveAdapter instanceof AdapterInterface)
		{
			return $this->slaveAdapter;
		}
		
		return $this->adapter;
	}
	
	/**
	 * Set the slave database adapter.
	 * 
	 * @param AdapterInterface $slaveAdapter
	 * @return AbstractMapper
	 */
	public function setSlaveAdapter(AdapterInterface $slaveAdapter)
	{
		$this->slaveAdapter = $slaveAdapter;
		
		return $this;
	}
	
	/**
	 * Retrieve the hydrator.
	 * 
	 * @return HydratorInterface
	 */
	public function getHydrator()
	{
		if (!$this->hydrator instanceof HydratorInterface)
		{
			$this->hydrator = new ClassMethods();
		}
		
		return $this->hydrator;
	}
	
	/**
	 * Set the hydrator.
	 * 
	 * @param HydratorInterface $hydrator
	 * @return AbstractMapper
	 */
	public function setHydrator(HydratorInterface $hydrator)
	{
		$this->hydrator = $hydrator;
		
		return $this;
	}
	
	/**
	 * Retrieve the prototype object.
	 * 
	 * @return object
	 */
	public function getPrototype()
	{
		return $this->prototype;
	}
	
	/**
	 * Set the prototype object.
	 * 
	 * @param object $prototype
	 * @return AbstractMapper
	 */
	public function setPrototype($prototype)
	{
		$this->prototype = $prototype;
		
		return $this;
	}
	
	/**
	 * Retrieve the Sql object.
	 * 
	 * @return Sql
	 * @codeCoverageIgnore
	 */
	protected function getSql()
	{
		if (!$this->sql instanceof Sql)
		{
			$this->sql = new Sql($this->getAdapter());
		}
		
		return $this->sql;
	}
	
	/**
	 * Retrieve the slave Sql object.
	 *
	 * @return Sql
	 * @codeCoverageIgnore
	 */
	protected function getSlaveSql()
	{
		if (!$this->slaveSql instanceof Sql)
		{
			$this->sql = new Sql($this->getSlaveAdapter());
		}
		
		return $this->sql;
	}
	
	/**
	 * Retrieve the database table name or return the once provided.
	 *
	 * @return string
	 * @codeCoverageIgnore
	 */
	protected function getTableName()
	{
		return $this->tableName;
	}
	
	/**
	 * Retrieve the Select object.
	 * 
	 * @param null|string|array|TableIdentifier $table
	 * @return Select
	 * @codeCoverageIgnore
	 */
	protected function getSelect($table = null)
	{
		if ($table === null)
		{
			$table = $this->getTableName();
		}
		
		return $this->initialize()->getSlaveSql()->setTable($table)->select();
	}
	
	/**
	 * Convert an entity to an array using a hydrator.
	 * 
	 * @param array|object $entity
	 * @param HydratorInterface $hydrator
	 * @return array
	 * @throws InvalidArgumentException
	 * @codeCoverageIgnore
	 */
	protected function entityToArray($entity, HydratorInterface $hydrator = null)
	{
		if (is_array($entity))
		{
			return $entity;
		}
		else if (is_object($entity))
		{
			if ($hydrator === null)
			{
				$hydrator = $this->getHydrator();
			}
			
			return $hydrator->extract($entity);
		}
		
		throw new InvalidArgumentException('Entity passed to the database mapper should be an array or object.');
	}
}