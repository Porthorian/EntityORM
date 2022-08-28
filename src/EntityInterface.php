<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm;

use Porthorian\EntityOrm\Model\ModelInterface;

interface EntityInterface
{
	/**
	 * Takes all current values inside the model and inserts them into the db entity
	 * @throws EntityException - if the insertion query fails some how.
	 * @return an Instance of ModelInterface
	 */
	public function store() : ModelInterface;

	/**
	 * Update the db entity based on the primary key of the model.
	 * @param $params - These are the fields that will be pulled from the model
	 * Ex ['registration_time', 'date_of_birth']
	 * @throws InvalidArgumentException - If the column does not exist inside the model.
	 * @return bool
	*/
	public function update(array $params = []) : bool;

	/**
	 * Delete the entity based on the primary key of the model.
	 * @throws EntityException if the model object is not initialized
	 * @return bool
	 */
	public function delete() : bool;

	/**
	 * @param pk_value - Primary key value
	 * @return A model that extends an instance of ModelInterface
	 */
	public function find(string|int $pk_value) : ModelInterface;

	/**
	 * The database in where the db_table is located.
	 */
	public function getCollectionName() : string;

	/**
	 * The table that we will be manipulating
	 */
	public function getCollectionTable() : string;

	/**
	 * Primary key column name for the database table
	 */
	public function getCollectionPrimaryKey() : string;

	/**
	 * Houses the namespace path to the model for the entity
	 */
	public function getModelPath() : string;

	/**
	 * Get the current ModelInterface stored in the Entity
	 * @return ModelInterface
	 */
	public function getModel() : ModelInterface;

	/**
	 * Set the model on the instance of this entity.
	 */
	public function setModel(ModelInterface $model) : void;

	/**
	 * Set and return a new object with the new model set.
	 */
	public function withModel(ModelInterface $model) : self;

	/**
	 * Get a Cacheable string that can be used as a unique identifier to the entity model.
	 * Ex: your_schema:your_table:your_primary_key_column:your_primary_key_value
	 * @param $pk_value = Primary Key Value
	 * @return string
	 */
	public function getCacheKey(string|int $pk_value = 0, string $pk_name = '') : string;
}
