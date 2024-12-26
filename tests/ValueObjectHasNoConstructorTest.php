<?php
namespace Apie\Tests\ApiePhpstanRules;

use Apie\ApiePhpstanRules\ValueObjectHasNoConstructor;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ValueObjectWithArrayShouldBeComposite>
 */
class ValueObjectHasNoConstructorTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ValueObjectHasNoConstructor($this->createReflectionProvider());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('ruleProvider')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function testLegacyRule(array $rules, string... $fileToAnalyse): void
    {
        $this->analyse($fileToAnalyse, $rules);
    }

    public static function ruleProvider(): iterable
    {
        yield [
            [
                ["Class 'ValueObjectWithoutConstructor' is a value object, but it has no constructor.", 7]
            ],
            __DIR__ . '/Fixtures/ValueObjectWithoutConstructor.php',
        ];
        yield [
            [
                ["Class 'ValueObjectWithBaseClass' is a value object, but it has no constructor.", 4]
            ],
            __DIR__ . '/Fixtures/ValueObjectWithBaseClass.php',
        ];
        yield [
            [],
            __DIR__ . '/Fixtures/AbstractValueObjectWithoutConstructor.php',
        ];
    }
}
