<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Validator;

use Porthorian\Utility\String\StringUtility;

class BaseValidator extends Validator
{
	/**
	 * Enforce a minimum length to a string. If its less than the minimum will return false.
	 * Otherwise true.
	 * @return bool
	 */
	public function minLength(string $value, int $min) : bool
	{
		if (strlen($value) < $min)
		{
			$this->addError(StringUtility::humanReadable($this->getCurrentEntityProperty()) . ' is below the minimum length of ' . $min);
			return false;
		}
		return true;
	}

	/**
	 * Enforce a maximum length to a string. If its greater than max return false.
	 * Otherwise true.
	 * @return bool
	 */
	public function maxLength(string $value, int $max) : bool
	{
		if (strlen($value) > $max)
		{
			$this->addError(StringUtility::humanReadable($this->getCurrentEntityProperty()) . ' has exceeded maximum length of ' . $max);
			return false;
		}
		return true;
	}

	public function betweenLengths(string $value, int $min, int $max) : bool
	{
		$length = strlen($value);
		if (!($length > $min && $length < $max))
		{
			$this->addError(StringUtility::humanReadable($this->getCurrentEntityProperty()) . ' is not between the min length of '.$min.' and the max length of '.$max);
			return false;
		}
		return true;
	}
}
