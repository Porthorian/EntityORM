<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Tests;

class ModelChild2 extends ModelChild
{
	protected string $property1 = 'hello';
	protected string $property2 = 'public';

	private string $hello = 'world';

	public function getProperty1() : string
	{
		return $this->property1;
	}

	public function setProperty1(string $value) : void
	{
		$this->property1 = $value;
	}

	public function getProperty2() : string
	{
		return $this->property2;
	}

	public function setProperty2(string $value) : void
	{
		$this->property2 = $value;
	}

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
