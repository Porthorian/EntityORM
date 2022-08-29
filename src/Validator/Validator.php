<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Validator;

use Porthorian\EntityOrm\Model\BaseModelInterface;
use Porthorian\Utility\Error\ErrorTrait;

abstract class Validator
{
	use ErrorTrait;

	protected BaseModelInterface $model;

	/**
	 * Rules that the validators will run against the fields.
	 * [
	 *	'validator' => ['column1' => [], 'column2' => [30]]
	 * ]
	 */
	private array $rules = [];

	/**
	 * Will continue to validate the rest of the validator values if one fails
	 * But will not validate any other validators if 1 fails.
	 */
	protected bool $stop_on_first_validator_fail = true;

	/**
	 * If any rule in the validator fails, this will fail the whole function.
	 * If this is set to true.
	 */
	protected bool $stop_on_first_rule_fail = false;

	/**
	 * Which entity property is being validated during current validate iteration.
	 * This gets reset every time validate is called.
	 * But will remain with the last property that was called whether successful or failure.
	 */
	private string $entity_property = '';

	////
	// Public Routines
	////

	public function __construct(BaseModelInterface $model)
	{
		$this->model = $model;
	}

	/**
	 * @param $validator - The validation function that will be run on the following columns
	 * @param $rule_columns - The fields to search for from the model array
	 * @param $params - Any additional parameters you may need for a specific column that is fed into the function in the order they are in the array.
	 * Ex ->addRule('validateAge', ['date_of_birth', 'registration_time'], ['date_of_birth' => 16])
	 * Ex ->addRule('validateAge', ['date_of_birth', 'registration_time'], ['date_of_birth' => [16, 65]])
	 * The 16 will be fed in as an additional parameter when the function validateAge is called on date_of_birth, but when validateAge is ran on registration_time
	 * It uses the default parameter of the function.
	 * @return void
	 */
	final public function addRule(string $validator, array $rule_columns, array $params = []) : void
	{
		foreach ($rule_columns as $column)
		{
			$this->rules[$validator][$column] = $params[$column] ?? [];
		}
	}

	final public function getRules() : array
	{
		return $this->rules;
	}

	/**
	 * Gets the rule columns that are assigned to the validation function.
	 * @param $validator
	 * @return array
	 */
	final public function getRuleColumns(string $validator) : array
	{
		return $this->getRules()[$validator] ?? [];
	}

	final public function getModel() : BaseModelInterface
	{
		return $this->model;
	}

	/**
	 * Validate the model values based on the validators and the rule column's specified
	 * @return bool true on succes or false on failure
	 */
	public function validate() : bool
	{
		$this->setCurrentEntityProperty('');
		$failure = true;
		$fields = $this->getModel()->toArray();
		foreach ($this->getRules() as $validator => $rule_columns)
		{
			$result = true;
			foreach ($rule_columns as $rule_column => $params)
			{
				/**
				 * This allows for developers to specify a parameter or parameters.
				 * @see addRule
				 * Ex ['date_of_birth' => 16]
				 * Ex ['date_of_birth' => [16, 65]]
				 */
				$shift_params = [$params];
				if (is_array($params))
				{
					$shift_params = $params;
				}
				array_unshift($shift_params, $fields[$rule_column]);

				$this->setCurrentEntityProperty($rule_column);

				$result = $result && call_user_func_array([$this, $validator], $shift_params);
				if ($result === false && $this->isStopOnRuleFail())
				{
					break 2;
				}
			}

			if ($result === false)
			{
				$failure = false;
			}

			if (!$result && $this->isStopOnValidatorFail())
			{
				break;
			}
		}

		$error_fail = count($this->getError()) === 0;
		if ($error_fail === true && $failure === false)
		{
			$this->addError('Unknown validation error has occurred.');
			trigger_error('Validation method returned false without giving an error. Class:'.get_class($this), E_USER_WARNING);
			return false;
		}

		return $error_fail;
	}

	////
	// Protected Routines
	////

	final protected function isStopOnValidatorFail() : bool
	{
		return $this->stop_on_first_validator_fail;
	}

	final protected function isStopOnRuleFail() : bool
	{
		return $this->stop_on_first_rule_fail;
	}

	final protected function setCurrentEntityProperty(string $entity_property) : void
	{
		$this->entity_property = $entity_property;
	}

	final protected function getCurrentEntityProperty() : string
	{
		return $this->entity_property;
	}
}
