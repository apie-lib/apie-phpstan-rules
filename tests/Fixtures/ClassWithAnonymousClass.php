<?php
namespace Apie\Tests\ApiePhpstanRules\Fixtures;

use Apie\Core\Dto\DtoInterface;

class ClassWithAnonymousClass
{
    public function create()
    {
        return new class() implements DtoInterface {
        };
    }
}
