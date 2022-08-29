<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Tests;

class ModelChild2 extends ModelChild
{
	protected string $property1 = 'hello';
	protected string $property2 = 'public';

	public function toArray() : array
	{
		return [
			'property1' => $this->property1,
			'property2' => $this->property2
		];
	}

	public function toPublicArray() : array
	{
		return [
			'property2' => $this->property2
		];
	}

	public function getEntityPath() : string
	{
		return '\Porthorian\EntityOrm\Tests\wrong';
	}
}
