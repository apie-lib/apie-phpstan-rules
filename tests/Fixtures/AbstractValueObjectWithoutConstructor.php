<?php
namespace Apie\Tests\ApiePhpstanRules\Fixtures;

use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use UnitEnum;

abstract class AbstractValueObjectWithoutConstructor implements ValueObjectInterface
{
    private array|string|int|float|bool|UnitEnum|null $mixed;

    public static function fromNative(mixed $input): self
    {
        $item = new self();
        $item->mixed = $input;
        return $item;
    }
    /**
     * @return array<string|int, mixed>|string|int|float|bool|UnitEnum|null
     */
    public function toNative(): array|string|int|float|bool|UnitEnum|null
    {
        return $this->mixed;
    }
}
