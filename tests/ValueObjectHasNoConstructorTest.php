<?php
namespace Apie\Tests\ApiePhpstanRules;

use Apie\ApiePhpstanRules\ValueObjectHasNoConstructor;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ValueObjectHasNoConstructor>
 */
class ValueObjectHasNoConstructorTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        // getRule() method needs to return an instance of the tested rule
        return new ValueObjectHasNoConstructor();
    }

    /**
     * @dataProvider ruleProvider
     */
    public function testRule(array $rules, string... $fileToAnalyse): void
    {
        $this->analyse($fileToAnalyse, $rules);
    }

    public function ruleProvider(): iterable
    {
        yield [
            [
                ["Class 'ValueObjectWithoutConstructor' is a value object, but it has no constructor.", 7],
            ],
            __DIR__ . '/Fixtures/ValueObjectWithoutConstructor.php',
        ];
        yield [
            [
                /*["Class 'ValueObjectWithBaseClass' is a value object, but it has no constructor.", 7],*/ // TODO
            ],
            __DIR__ . '/Fixtures/ValueObjectWithBaseClass.php',
            __DIR__ . '/Fixtures/AbstractValueObjectWithoutConstructor.php',
        ];
        yield [
            [],
            __DIR__ . '/Fixtures/AbstractValueObjectWithoutConstructor.php',
        ];
    }
}
