<?php
namespace Apie\Tests\ApiePhpstanRules;

use Apie\ApiePhpstanRules\ObjectShouldNotImplementMultipleInterfaces;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ObjectShouldNotImplementMultipleInterfaces>
 */
class ObjectShouldNotImplementMultipleInterfacesTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ObjectShouldNotImplementMultipleInterfaces($this->createReflectionProvider());
    }

    /**
     * @dataProvider ruleProvider
     * @runInSeparateProcess
     */
    public function testLegacyRule(array $rules, string... $fileToAnalyse): void
    {
        $this->analyse($fileToAnalyse, $rules);
    }

    public function ruleProvider(): iterable
    {
        yield [
            [
                ["Class 'ObjectWithMultipleInterfaces' has conflicting interfaces: Apie\Core\Entities\EntityInterface, Apie\Core\Dto\DtoInterface", 8],
            ],
            __DIR__ . '/Fixtures/ObjectWithMultipleInterfaces.php',
        ];
        yield [
            [],
            __DIR__ . '/Fixtures/ClassWithAnonymousClass.php',
        ];
        yield [
            [
            ],
            __DIR__ . '/Fixtures/ValueObjectWithoutConstructor.php',
        ];
    }
}
