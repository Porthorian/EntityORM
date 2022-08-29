<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Validator;

use Porthorian\Utility\String\StringUtility;

class BaseValidator extends Validator
{
	/**
	 * @return true on length greater than min|false on failure
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
	 * @return true on length less than max|false on failure
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
}
