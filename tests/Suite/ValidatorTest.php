<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Tests\Suite;

use PHPUnit\Framework\TestCase;
use Porthorian\EntityOrm\Validator\BaseValidator;
use Porthorian\EntityOrm\Tests\ModelChild2;

class ValidatorTest extends TestCase
{
	public function testMinLength()
	{
		$child = new ModelChild2();
		$child->setInitializedFlag(true);
		$validator = new BaseValidator($child);
		$validator->addRule('minLength', ['property1'], ['property1' => 6]);

		$this->assertFalse($validator->validate());
		$this->assertEquals('Property1 is below the minimum length of 6', $validator->getLastError());

		$validator = new BaseValidator($child);
		$validator->addRule('minLength', ['property1'], ['property1' => 3]);
		$this->assertTrue($validator->validate());
	}

	public function testMaxLength()
	{
		$child = new ModelChild2();
		$child->setInitializedFlag(true);
		$validator = new BaseValidator($child);
		$validator->addRule('maxLength', ['property1'], ['property1' => 2]);

		$this->assertFalse($validator->validate());
		$this->assertEquals('Property1 has exceeded maximum length of 2', $validator->getLastError());

		$validator = new BaseValidator($child);
		$validator->addRule('maxLength', ['property1'], ['property1' => 7]);
		$this->assertTrue($validator->validate());
	}

	public function testGetRuleColumns()
	{
		$child = new ModelChild2();
		$child->setInitializedFlag(true);
		$validator = new BaseValidator($child);
		$validator->addRule('minLength', ['property1'], ['property1' => 6]);

		$this->assertEquals(['property1' => 6], $validator->getRuleColumns('minLength'));
		$this->assertEmpty($validator->getRuleColumns('unknown'));
	}
}
