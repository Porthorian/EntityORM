<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm;

use Porthorian\EntityOrm\Model\ModelInterface;
use Porthorian\Utility\Cache\CacheTrait;
use Porthorian\Utility\Metadata\ClassMetadata;

abstract class Entity implements EntityInterface
{
	use CacheTrait {
		setCacheItem as protected;
		setCacheItemIfNotSet as protected;
		getCacheItem as protected;
		hasCacheItem as protected;
		deleteCacheItem as protected;
		resetCache as protected;
	}

	/**
	 * Houses the model object based on the model path
	 */
	protected ModelInterface $model;
	/**
	 * Model Metadata information
	 */
	protected ClassMetadata $metadata;

	protected bool $entity_cache = false;

	////
	// Abstract Routines
	////
	abstract public function store() : ModelInterface;
	abstract public function update(array $params = []) : bool;
	abstract public function delete() : bool;
	abstract public function find(string|int $pk_value) : ModelInterface;
	abstract public function getCollectionName() : string;
	abstract public function getCollectionTable() : string;
	abstract public function getCollectionPrimaryKey() : string;
	abstract public function getModelPath() : string;

	////
	// Public Routines
	////

	public function getModel() : ModelInterface
	{
		return $this->model;
	}

	public function setModel(ModelInterface $model) : void
	{
		if (!isset($this->model) || get_class($this->getModel()) != get_class($model))
		{
			$this->intializeMetadata($model);
		}
		$this->model = $model;
	}

	public function withModel(ModelInterface $model) : self
	{
		$object = clone $this;
		$object->setModel($model);
		return $object;
	}

	/**
	 * @see EntityInterface
	 */
	public function getCacheKey(string|int $pk_value = 0, string $pk_name = '') : string
	{
		$model = $this->getModel();

		$key = $this->getCollectionName() . ':';
		$key .= $this->getCollectionTable() . ':';

		$name = $pk_name;
		if (empty($pk_name))
		{
			$name = $this->getCollectionPrimaryKey();
		}
		$key .= $name . ':';

		$pk_key = $pk_value;
		if (empty($pk_key))
		{
			$pk_key = $model->getPrimaryKey();
		}

		$key .= $pk_key;

		return $key;
	}

	/**
	 * Determine if we wanna look to see if the entity is cached in our trait.
	 * @return void
	 */
	final public function setEntityCache(bool $cachable) : void
	{
		$this->entity_cache = $cachable;
	}

	////
	// Protected Routines
	////

	/**
	* Check to see if the entry should be used in the trait cache first?
	* Make sure to update the cache with anything being added or changed.
	* @return bool
	*/
	final protected function useEntityCache() : bool
	{
		return $this->entity_cache;
	}

	protected function resetModel() : void
	{
		$model = $this->getModel();
		$model->setInitializedFlag(false);
		$model->reset();
		$this->setModel($model);
	}

	////
	// Private Routines
	////

	private function intializeMetadata(ModelInterface $model) : void
	{
		$this->metadata = new ClassMetadata($model);
	}
}
