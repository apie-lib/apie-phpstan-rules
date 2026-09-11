<?php
namespace Apie\ApiePhpstanRules;

use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Class_>
 */
final class RegexValueObjectShouldImplementInterface implements Rule
{
    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @param Class_ $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $nodeName = $node->name->toString();
        if ($node->isAnonymous() || str_starts_with($nodeName, 'Anonymous')) {
            return [];
        }
        $class = $this->getClass($node, $scope);
        $nativeReflection = $class->getNativeReflection();
        if (
            in_array(IsStringWithRegexValueObject::class, $nativeReflection->getTraitNames(), true)
            && !$class->implementsInterface(HasRegexValueObjectInterface::class)
        ) {
            return [
                RuleErrorBuilder::message(
                    sprintf(
                        "Class '%s' uses IsStringWithRegexValueObject trait, but does not implement HasRegexValueObjectInterface.",
                        $nodeName
                    )
                )->identifier('apie.regex.value.object.interface')
                ->build()
            ];
        }
        return [];
    }

    private function getClass(Class_ $node, Scope $scope): ClassReflection
    {
        return $this->reflectionProvider->getClass($scope->getNamespace() . '\\' . $node->name->toString());
    }
}
