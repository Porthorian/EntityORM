<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Tests;

use PHPUnit\Framework\TestCase;
use Porthorian\EntityOrm\Tests\EntityChild;
use Porthorian\EntityOrm\Model\ModelInterface;
use Porthorian\EntityOrm\Tests\ModelChild;
use Porthorian\EntityOrm\Tests\ModelChild2;

class EntityTest extends TestCase
{
	public function testGetModel()
	{
		$entity = (new EntityChild())->withModel(new ModelChild());
		$this->assertInstanceOf(ModelInterface::class, $entity->getModel());
		$this->assertInstanceOf(ModelChild::class, $entity->getModel());
	}

	public function testSetModel()
	{
		$entity = new EntityChild();
		$entity->setModel(new ModelChild());
		$this->assertInstanceOf(ModelInterface::class, $entity->getModel());
		$this->assertInstanceOf(ModelChild::class, $entity->getModel());

		$entity->setModel(new ModelChild2());
		$this->assertInstanceOf(ModelInterface::class, $entity->getModel());
		$this->assertInstanceOf(ModelChild2::class, $entity->getModel());
	}

	public function testWithModel()
	{
		$entity = (new EntityChild())->withModel(new ModelChild());
		$this->assertInstanceOf(ModelInterface::class, $entity->getModel());
		$this->assertInstanceOf(ModelChild::class, $entity->getModel());
	}

	public function testGetCacheKey()
	{
		$entity = (new EntityChild())->withModel(new ModelChild());
		$this->assertEquals('test_schema:test:test_column:0', $entity->getCacheKey());
		$this->assertEquals('test_schema:test:new_name:value', $entity->getCacheKey('value', 'new_name'));
	}

	public function testSettingEntityCache()
	{
		$entity = (new EntityChild())->withModel(new ModelChild());
		$entity->setEntityCache(true);

		$model = $entity->store();

		$model->test = 'no';
		$entity->setModel($model);

		$this->assertEquals('hello', $entity->find(0)->test);
	}

	public function testResetModel()
	{
		$model = new ModelChild();
		$model->test = 'new variable';
		$entity = (new EntityChild())->withModel($model);
		$this->assertEquals('new variable', $entity->getModel()->test);

		$entity->delete();

		$this->assertEquals('hello', $entity->getModel()->test);
	}
}
