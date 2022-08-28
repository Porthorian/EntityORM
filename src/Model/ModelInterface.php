<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Model;

use Porthorian\EntityOrm\EntityInterface;

interface ModelInterface
{
	/**
	 * The primary key identified value for the model.
	 * This is a unique identifier.
	 */
	public function setPrimaryKey(string|int $value) : void;

	/**
	 * Get the primary key for the model.
	 */
	public function getPrimaryKey() : string|int;

	/**
	 * The class path to the instance that implements EntityInterface that is related to this model.
	 */
	public function getEntityPath() : string;

	/**
	 * Create an Entity instance with the existing model data that are related.
	 */
	public function createEntity() : EntityInterface;
}
