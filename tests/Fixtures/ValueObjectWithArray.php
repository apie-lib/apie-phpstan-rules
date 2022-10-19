<?php
namespace Apie\Tests\ApiePhpstanRules\Fixtures;

use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\Core\ValueObjects\Utils;

class ValueObjectWithArray implements ValueObjectInterface
{
    private array $input;

    public function __construct(mixed $input)
    {
        $this->input = Utils::toArray($input);
    }

    public static function fromNative(mixed $input): self
    {
        return new self($input);
    }

    public function toNative(): array
    {
        return $this->input;
    }
}
