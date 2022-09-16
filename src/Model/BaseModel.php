<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Model;

use ReflectionException;
use Porthorian\Utility\Metadata\ClassMetadata;
use Porthorian\Utility\Json\JsonWrapper;

abstract class BaseModel implements BaseModelInterface
{
	private bool $initialized_flag = false;

	private ClassMetadata $metadata;

	////
	// Abstract Routines
	////

	abstract public function toArray() : array;
	abstract public function toPublicArray() : array;

	////
	// Public Routines
	////

	public function __construct()
	{
		$this->metadata = new ClassMetadata($this);
		$this->reset();
	}

	public function toJSON() : string
	{
		return JsonWrapper::json($this->toArray());
	}

	public function toPublicJSON() : string
	{
		return JsonWrapper::json($this->toPublicArray());
	}

	public function reset() : void
	{
		foreach ([$this->metadata->getPublicProperties(), $this->metadata->getProtectedProperties()] as $properties)
		{
			foreach ($properties as $property)
			{
				$name = $property->getName();

				if ($property->hasDefaultValue())
				{
					$this->$name = $property->getDefaultValue();
					continue;
				}

				unset($this->$name);
			}
		}
	}

	////
	// Final Public Routines
	////

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
		$reflection = $this->metadata->getReflection();
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

			$property->setValue($this, $value);
		}
	}

	////
	// Final protected methods
	////

	final protected function toProtectedPropsArray() : array
	{
		$output = [];
		foreach ($this->metadata->getProtectedProperties() as $property)
		{
			if (!$property->isInitialized($this))
			{
				continue;
			}

			$name = $property->getName();
			$output[$name] = $this->$name;
		}
		return $output;
	}

	final protected function toPublicPropsArray() : array
	{
		$output = [];
		foreach ($this->metadata->getPublicProperties() as $property)
		{
			if (!$property->isInitialized($this))
			{
				continue;
			}

			$name = $property->getName();
			$output[$name] = $this->$name;
		}
		return $output;
	}

	/**
	 * This isn't apart of the interface as this is a specific implementation detail if it is needed.
	 */
	final protected function getMetadata() : ClassMetadata
	{
		return $this->metadata;
	}
}
