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
 * Value object without constructor is often a mistake.
 *
 * Because if there is no constructor people can bypass caling fromNative and call new ValueObject() without
 * arguments.
 * 
 * @implements Rule<Class_>
 */
final class ValueObjectHasNoConstructor implements Rule
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
        if ($node->isAbstract() || str_starts_with($nodeName, 'Anonymous') || $node->isAnonymous() || $node->getMethod('__construct')) {
            return [];
        }
        $class = $this->getClass($node, $scope);
        if ($class->implementsInterface(ValueObjectInterface::class) && !$class->hasConstructor()) {
            return [
                __CLASS__ => sprintf(
                    "Class '%s' is a value object, but it has no constructor.",
                    $node->name->toString()
                )
            ];
        }
        return [
        ];
    }

    private function getClass(Class_ $node, Scope $scope): ClassReflection
    {
        return $this->reflectionProvider->getClass($scope->getNamespace() . '\\' . $node->name->toString());
    }
}
