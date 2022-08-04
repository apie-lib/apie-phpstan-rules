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

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/Fixtures/ValueObjectWithoutConstructor.php'], [
            [
                "Class 'ValueObjectWithoutConstructor' is a value object, but it has no constructor.",
                7, // asserted error line
            ],
        ]);
    }
}
