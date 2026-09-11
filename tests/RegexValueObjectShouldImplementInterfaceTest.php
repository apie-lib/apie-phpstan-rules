<?php
namespace Apie\Tests\ApiePhpstanRules;

use Apie\ApiePhpstanRules\RegexValueObjectShouldImplementInterface;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<RegexValueObjectShouldImplementInterface>
 */
class RegexValueObjectShouldImplementInterfaceTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new RegexValueObjectShouldImplementInterface($this->createReflectionProvider());
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
                ["Class 'RegexValueObjectWithoutInterface' uses IsStringWithRegexValueObject trait, but does not implement HasRegexValueObjectInterface.", 6],
            ],
            __DIR__ . '/Fixtures/RegexValueObjectWithoutInterface.php',
        ];
    }
}
