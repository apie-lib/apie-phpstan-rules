<?php
namespace Apie\Tests\ApiePhpstanRules;

use Apie\ApiePhpstanRules\EntityGetIdShouldBeSpecific;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<EntityGetIdSholdBeSpecific>
 */
class EntityGetIdShouldBeSpecificTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new EntityGetIdShouldBeSpecific($this->createReflectionProvider());
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
                ["Class 'EntityWithNoSpecificIdentifier' is an entity, but the getId() implementation has still IdentifierInterface return type.", 8]
            ],
            __DIR__ . '/Fixtures/EntityWithNoSpecificIdentifier.php',
        ];
    }
}
