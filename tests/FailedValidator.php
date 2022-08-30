<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Tests;

use Porthorian\EntityOrm\Validator\Validator;

class FailedValidator extends Validator
{
	public function failedValidationNoError(string $value)
	{
		return false;
	}
}
