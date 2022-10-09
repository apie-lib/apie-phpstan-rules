<?php
namespace Apie\Tests\ApiePhpstanRules\Fixtures;

use Apie\Core\Dto\DtoInterface;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\IdentifierInterface;

class ObjectWithMultipleInterfaces implements DtoInterface, EntityInterface
{
    /**
     * @var string
     */
    public $noTypehint;

    public function getId(): IdentifierInterface
    {
        return new ObjectWithMultipleInterfacesId('input');
    }
}