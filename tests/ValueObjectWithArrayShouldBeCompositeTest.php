<?php
namespace Apie\Tests\ApiePhpstanRules;

use Apie\ApiePhpstanRules\ValueObjectHasNoConstructor;
use Apie\ApiePhpstanRules\ValueObjectWithArrayShouldBeComposite;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ValueObjectHasNoConstructor>
 */
class ValueObjectWithArrayShouldBeCompositeTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ValueObjectWithArrayShouldBeComposite($this->createReflectionProvider());
    }

    /**
     * @dataProvider ruleProvider
     */
    public function testLegacyRule(array $rules, string... $fileToAnalyse): void
    {
        $this->analyse($fileToAnalyse, $rules);
    }

    public function ruleProvider(): iterable
    {
        yield [
            [
                ["Class 'ValueObjectWithArray' is a value object that returns an array, but it does not use CompositeTrait.", 7],
            ],
            __DIR__ . '/Fixtures/ValueObjectWithArray.php',
        ];
        yield [
            [],
            __DIR__ . '/Fixtures/AbstractValueObjectWithoutConstructor.php',
        ];
    }
}
