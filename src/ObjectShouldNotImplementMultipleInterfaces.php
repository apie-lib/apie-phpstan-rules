<?php
namespace Apie\ApiePhpstanRules;

use Apie\Core\Dto\DtoInterface;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use Throwable;

/**
 * Ensure a class is not a value object and an entity at the same time for example.
 * @implements Rule<Class_>
 */
final class ObjectShouldNotImplementMultipleInterfaces implements Rule
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
        $interfacesToCheck = [ValueObjectInterface::class, DtoInterface::class, EntityInterface::class, Throwable::class];
        $conflicted = [];
        foreach ($interfacesToCheck as $interfaceToCheck) {
            if ($class->implementsInterface($interfaceToCheck)) {
                foreach ($interfacesToCheck as $shouldNotBeImplemented) {
                    if ($shouldNotBeImplemented === $interfaceToCheck) {
                        continue;
                    }
                    if ($class->implementsInterface($shouldNotBeImplemented)) {
                        $conflicted[$shouldNotBeImplemented] = $shouldNotBeImplemented;
                    }
                }
            }
        }
        if (!empty($conflicted)) {
            return [
                __CLASS__ => sprintf(
                    "Class '%s' has conflicting interfaces: %s",
                    $nodeName,
                    implode(', ', $conflicted)
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
