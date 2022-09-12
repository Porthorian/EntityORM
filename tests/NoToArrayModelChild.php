<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Tests;

use Porthorian\EntityOrm\Model\Model;

class NoToArrayModelChild extends Model
{
	public string $test_world = 'world';
	protected string $test = 'hello';

	public function toPublicArray() : array
	{
		return [];
	}

	public function setPrimaryKey(string|int $value) : void
	{
		return;
	}

	public function getPrimaryKey() : string|int
	{
		return 0;
	}

	public function getEntityPath() : string
	{
		return '\Porthorian\EntityOrm\Tests\EntityChild';
	}
}
