<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm;

use Exception;
use Throwable;

class ModelException extends Exception
{
	public function __construct(string $message, ?Throwable $previous = null)
	{
		parent::__construct($message, 136, $previous);
	}
}
