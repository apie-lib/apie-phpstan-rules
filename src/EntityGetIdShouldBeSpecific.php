<?php
namespace Apie\ApiePhpstanRules;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\IdentifierInterface;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * EntityInterface only implements getId() with return type IdentifierInterface. To get better
 * reflection data it is better if the entity returns the specific identifier class and not identifierInterface.
 *
 * @implements Rule<Class_>
 */
final class EntityGetIdShouldBeSpecific implements Rule
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
        if ($node->isAbstract() || str_starts_with($nodeName, 'Anonymous') || $node->isAnonymous()) {
            return [];
        }
        $class = $this->getClass($node, $scope);
        if ($class->implementsInterface(EntityInterface::class)) {
            $method = $class->getMethod('getId', $scope);
            foreach ($method->getVariants() as $variant) {
                $type = $variant->getNativeReturnType();
                if ($type->isObject()->yes() && in_array(IdentifierInterface::class, $type->getReferencedClasses())) {
                    return [
                        RuleErrorBuilder::message(
                            sprintf(
                                "Class '%s' is an entity, but the getId() implementation has still IdentifierInterface return type.",
                                $nodeName
                            )
                        )->identifier('apie.get.id.specific')
                        ->build()
                    ];
                }
            }
        }
        return [
        ];
    }

    private function getClass(Class_ $node, Scope $scope): ClassReflection
    {
        return $this->reflectionProvider->getClass($scope->getNamespace() . '\\' . $node->name->toString());
    }
}
