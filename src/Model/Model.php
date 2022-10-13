<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Model;

use Porthorian\EntityOrm\EntityInterface;

abstract class Model extends BaseModel implements ModelInterface
{
	////
	// Abstract Routines
	////
	
	abstract public function toArray() : array;
	abstract public function toPublicArray() : array;

	abstract public function setPrimaryKey(string|int $value) : void;
	abstract public function getPrimaryKey() : string|int;
	abstract public function getEntityPath() : string;

	////
	// Public Routines
	////

	public function createEntity() : EntityInterface
	{
		$entity = $this->getEntityPath();
		if (!class_exists($entity))
		{
			throw new ModelException('Unable to create entity as the class does not exist. Class: '.$entity);
		}

		$object = new $entity();
		$object->setModel($this);
		return $object;
	}
}
