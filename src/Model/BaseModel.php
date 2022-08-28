<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Model;

use Porthorian\Utility\Metadata\ClassMetadata;
use Porthorian\Utility\Json\JsonWrapper;

abstract class BaseModel implements BaseModelInterface
{
	private bool $initialized_flag = false;

	////
	// Abstract Routines
	////

	abstract public function reset() : void;
	abstract public function toArray() : array;
	abstract public function toPublicArray() : array;

	////
	// Public Routines
	////

	public function __construct()
	{
		$this->reset();
	}

	final public function toJSON() : string
	{
		return JsonWrapper::json($this->toArray());
	}

	final public function toPublicJSON() : string
	{
		return JsonWrapper::json($this->toPublicArray());
	}

	final public function isInitialized() : bool
	{
		return $this->initialized_flag;
	}

	final public function setInitializedFlag(bool $flag) : void
	{
		$this->initialized_flag = $flag;
	}

	/**
	* Set properties for the object based on the record given.
	*/
	final public function setModelProperties(array $record) : void
	{
		$metadata = new ClassMetadata($this);

		$reflection = $metadata->getReflection();
		foreach ($record as $column => $value)
		{
			try
			{
				$property = $reflection->getProperty($column);
			}
			catch (ReflectionException $e)
			{
				throw new ModelException('Property: '.$column.' has a problem and caused a reflection exception.', $e);
			}

			$property->setAccessible(true);
			$property->setValue($this, $value);
			$property->setAccessible(false);
		}
	}
}
