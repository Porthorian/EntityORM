<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Tests;

use Porthorian\EntityOrm\Entity;
use Porthorian\EntityOrm\Model\ModelInterface;

class EntityChild extends Entity
{
	public function store() : ModelInterface
	{
		if ($this->useEntityCache())
		{
			self::setCacheItem($this->getCacheKey(), clone $this->getModel());
		}

		return $this->getModel();
	}

	public function update(array $params = []) : bool
	{
		return true;
	}

	public function delete() : bool
	{
		$this->resetModel();
		return true;
	}

	public function find(string|int $pk_value) : ModelInterface
	{
		if ($this->useEntityCache() && self::hasCacheItem($this->getCacheKey($pk_value)))
		{
			return self::getCacheItem($this->getCacheKey($pk_value));
		}

		$this->resetModel();
		return $this->getModel();
	}

	public function getCollectionName() : string
	{
		return 'test_schema';
	}

	public function getCollectionTable() : string
	{
		return 'test';
	}

	public function getCollectionPrimaryKey() : string
	{
		return 'test_column';
	}

	public function getModelPath() : string
	{
		return '\Porthorian\EntityOrm\Tests\ModelChild';
	}
}
