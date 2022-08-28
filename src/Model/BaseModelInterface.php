<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Model;

interface BaseModelInterface
{
	/**
	 * Set defaults at at the beginning of the instance of the model.
	 */
	public function reset() : void;

	/**
	 * An array of all protected properties.
	 */
	public function toArray() : array;

	/**
	 * An array of protected properties that are user safe.
	 */
	public function toPublicArray() : array;

	/**
	 * Jsonify toArray attributes
	 */
	public function toJSON() : string;

	/**
	 * Jsonify toPublicArray attributes
	 */
	public function toPublicJSON() : string;

	/**
	 * Has the model been fully initialized with all properties filled?
	 */
	public function isInitialized() : bool;

	/**
	 * Set an initialized property that is coherint with isInitialized
	 */
	public function setInitializedFlag(bool $flag) : void;

	/**
	 * Set Model properties for the object based on the record given
	 */
	public function setModelProperties(array $record) : void;
}
