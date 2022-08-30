<?php

declare(strict_types=1);

namespace Porthorian\EntityOrm\Tests\Suite;

use PHPUnit\Framework\TestCase;
use Porthorian\EntityOrm\Validator\BaseValidator;
use Porthorian\EntityOrm\Tests\ModelChild2;
use Porthorian\EntityOrm\Tests\FailedValidator;

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

	public function testBetweenLengths()
	{
		$child = new ModelChild2();
		$child->setInitializedFlag(true);
		$validator = new BaseValidator($child);
		$validator->addRule('betweenLengths', ['property1'], ['property1' => [8, 10]]);

		$this->assertFalse($validator->validate());
		$this->assertEquals('Property1 is not between the min length of 8 and the max length of 10', $validator->getLastError());

		$validator = new BaseValidator($child);
		$validator->addRule('betweenLengths', ['property1'], ['property1' => [2, 10]]);
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

	public function testStopOnFirstValidatorFail()
	{
		$child = new ModelChild2();
		$child->setInitializedFlag(true);
		$validator = new BaseValidator($child);
		$validator->addRule('minLength', ['property1'], ['property1' => 10]);
		$validator->addRule('maxLength', ['property1'], ['property1' => 2]);
		$validator->setStopOnFirstValidatorFail(true);
		$this->assertFalse($validator->validate());
		$this->assertEquals('Property1 is below the minimum length of 10', $validator->getLastError());
	}

	public function testStopOnFirstRuleFail()
	{
		$child = new ModelChild2();
		$child->setInitializedFlag(true);
		$validator = new BaseValidator($child);
		$validator->addRule('minLength', ['property1'], ['property1' => 10, 'property2' => 25]);
		$validator->addRule('maxLength', ['property1'], ['property1' => 2]);
		$validator->setStopOnFirstRuleFail(true);

		$this->assertFalse($validator->validate());
		$this->assertEquals('Property1 is below the minimum length of 10', $validator->getLastError());
	}

	public function testTriggerWarning()
	{
		$child = new ModelChild2();
		$child->setInitializedFlag(true);

		$validator = new FailedValidator($child);
		$validator->addRule('failedValidationNoError', ['property1']);

		$this->expectNotice();
		$this->expectNoticeMessageMatches('/Validation method returned false without giving an error./');
		$this->assertFalse($validator->validate());
	}
}
