<?php
namespace Apie\Tests\ApiePhpstanRules\Fixtures;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Fixtures\Identifiers\UserWithAddressIdentifier;

class EntityWithNoSpecificIdentifier implements EntityInterface
{
    public function getId(): IdentifierInterface
    {
        return UserWithAddressIdentifier::createRandom();
    }
}
