<?php
namespace Apie\Tests\ApiePhpstanRules\Fixtures;

use Apie\Core\ValueObjects\IsStringWithRegexValueObject;

class RegexValueObjectWithoutInterface
{
    use IsStringWithRegexValueObject;

    public static function getRegularExpression(): string
    {
        return '/^[a-z]$/';
    }
}
