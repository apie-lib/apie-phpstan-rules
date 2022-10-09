<?php
namespace Apie\ApiePhpstanRules;

use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use ReflectionClass;

/**
 * Value object without constructor is often a mistake.
 *
 * Because if there is no constructor people can bypass caling fromNative and call new ValueObject() without
 * arguments.
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
        if ($node->isAbstract() || $node->getMethod('__construct')) {
            return [];
        }
        $class = $this->getClass($scope);
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

    private function getClass(Scope $scope): ClassReflection
    {
        return $this->reflectionProvider->getClass($scope->getNamespace() . '\\' . basename($scope->getFile(), '.php'));
    }
}
