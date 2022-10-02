<?php declare(strict_types=1);

namespace Porthorian\EntityOrm\Tests;

use Porthorian\EntityOrm\Model\Model;

class ModelChildEnum extends Model
{
	protected TestEnum $test_enum = TestEnum::ENUM1;

	public function toArray() : array
	{
		return ['test_enum' => $this->test_enum->value];
	}

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

