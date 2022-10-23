<?php
namespace Apie\ApiePhpstanRules;

use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;

/**
 * If value objects have array or stdClass as return type then they require the CompositeValueObject trait
 * as the spec has no defined behaviour for value objects with arrays otherwise.
 * 
 * @implements Rule<Class_>
 */
final class ValueObjectWithArrayShouldBeComposite implements Rule
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
        if ($class->implementsInterface(ValueObjectInterface::class)) {
            $method = $class->getMethod('toNative', $scope);
            foreach ($method->getVariants() as $variant) {
                if ($variant->getNativeReturnType() instanceof \PHPStan\Type\ArrayType) {
                    return [
                        __CLASS__ => sprintf(
                            "Class '%s' is a value object that returns an array, but it does not use CompositeTrait.",
                            $nodeName
                        )
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
