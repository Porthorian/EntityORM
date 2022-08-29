<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Model;

use Porthorian\EntityOrm\EntityException;
use Throwable;

class ModelException extends EntityException
{
	public function __construct(string $message, ?Throwable $previous = null)
	{
		parent::__construct($message, $previous);
	}
}
