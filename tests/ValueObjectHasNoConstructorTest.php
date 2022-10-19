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
            [],
            __DIR__ . '/Fixtures/AbstractValueObjectWithoutConstructor.php',
        ];
    }
}
