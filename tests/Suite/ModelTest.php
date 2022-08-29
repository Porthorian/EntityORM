<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Tests\Suite;

use PHPUnit\Framework\TestCase;
use Porthorian\EntityOrm\EntityInterface;
use Porthorian\EntityOrm\Model\ModelException;
use Porthorian\EntityOrm\Tests\EntityChild;
use Porthorian\EntityOrm\Tests\ModelChild;
use Porthorian\EntityOrm\Tests\ModelChild2;

class ModelTest extends TestCase
{
	public function testCreateEntity()
	{
		$child = new ModelChild();
		$entity = $child->createEntity();
		$this->assertInstanceOf(EntityChild::class, $entity);
		$this->assertInstanceOf(EntityInterface::class, $entity);

		$this->expectException(ModelException::class);
		$child = new ModelChild2();
		$child->createEntity();
	}

	public function testToArray()
	{
		$child = new ModelChild2();
		$this->assertNotEmpty($child->toArray());
		$this->assertEquals(['property1' => 'hello', 'property2' => 'public'], $child->toArray());
	}

	/**
	 * @depends testToArray
	 */
	public function testToJSON()
	{
		$child = new ModelChild2();
		$expected_string = '{"property1":"hello","property2":"public"}';
		$this->assertEquals($expected_string, $child->toJSON());
	}

	public function testToPublicArray()
	{
		$child = new ModelChild2();
		$this->assertNotEmpty($child->toArray());
		$this->assertEquals(['property2' => 'public'], $child->toPublicArray());
	}

	/**
	 * @depends testToPublicArray
	 */
	public function testToPublicJSON()
	{
		$child = new ModelChild2();
		$expected_string = '{"property2":"public"}';
		$this->assertEquals($expected_string, $child->toPublicJSON());
	}

	public function testIsInitialized()
	{
		$child = new ModelChild2();
		$this->assertFalse($child->isInitialized());
		$child->setInitializedFlag(true);
		$this->assertTrue($child->isInitialized());
	}

	public function testSetModelProperties()
	{
		$child = new ModelChild2();
		$child->setModelProperties(['property1' => 'new_world']);

		$this->assertEquals(['property1' => 'new_world', 'property2' => 'public'], $child->toArray());

		$this->expectException(ModelException::class);
		$child = new ModelChild2();
		$child->setModelProperties(['Unknown' => 'new_world']);
	}
}
