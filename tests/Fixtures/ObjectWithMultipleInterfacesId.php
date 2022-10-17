<?php
namespace Apie\Tests\ApiePhpstanRules\Fixtures;

use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\KebabCaseSlug;
use ReflectionClass;

class ObjectWithMultipleInterfacesId extends KebabCaseSlug implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(ObjectWithMultipleInterfaces::class);
    }
}
