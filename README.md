<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>apie-phpstan-rules</h1>






 [![Latest Stable Version](http://poser.pugx.org/apie/apie-phpstan-rules/v)](https://packagist.org/packages/apie/apie-phpstan-rules) [![Total Downloads](http://poser.pugx.org/apie/apie-phpstan-rules/downloads)](https://packagist.org/packages/apie/apie-phpstan-rules) [![Latest Unstable Version](http://poser.pugx.org/apie/apie-phpstan-rules/v/unstable)](https://packagist.org/packages/apie/apie-phpstan-rules) [![License](http://poser.pugx.org/apie/apie-phpstan-rules/license)](https://packagist.org/packages/apie/apie-phpstan-rules) [![PHP Version Require](http://poser.pugx.org/apie/apie-phpstan-rules/require/php)](https://packagist.org/packages/apie/apie-phpstan-rules) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-apie-phpstan-rules.svg)](https://apie-lib.github.io/projectCoverage/app/packages/apie-phpstan-rules/index.html)  

[![PHP Composer](https://github.com/apie-lib/apie-phpstan-rules/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/apie-phpstan-rules/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Adds phpstan rules specifically for Apie.

Usage:
```bash
composer require --dev apie/apie-phpstan-rules
```

and in your phpstan.neon the include to the neon file.
```yaml
includes
    - './vendor/apie/apie-phpstan-rules/apie-phpstan-rules.neon'
```

### Added phpstan rules

#### Entity getId() should be specific
The interface of an entity requires you to have a getId() method with IdentifierInterface as return type. For better
reflection it is better if you specify the class it returns instead.

```php
<?php
class ExampleEntity implements EntityInterface
{
    public function __construct(private readonly ExampleEntityIdentifier $id)
    {
    }

    public function getId(): IdentifierInterface
    {
        return $this->id;
    }
}
```

Should be fixed as:
```php
<?php
class ExampleEntity implements EntityInterface
{
    public function __construct(private readonly ExampleEntityIdentifier $id)
    {
    }

    public function getId(): ExampleEntityIdentifier
    {
        return $this->id;
    }
}
```

#### Object should not have conflicting interfaces
Do not make objects that are a value object and entity at the same time for example :)

#### Value object without constructor
This one is easy to miss when making a value object often when used in combination with composite value objects.


```php
<?php
class ExampleValueObject implements ValueObjectInterface
{
    use CompositeValueObject;

    private string $property;
}
```
Should be one of these:
```php
<?php
class ExampleValueObject implements ValueObjectInterface
{
    use CompositeValueObject;

    private string $property;

    private function __construct() {
    }
}
```
Or:
```php
<?php
class ExampleValueObject implements ValueObjectInterface
{
    use CompositeValueObject;

    public function __construct(private string $property)
    {
    }
}
```

#### Value Object with array should be composite
Imagine this value object:
```php
<?php
class ExampleValueObject implements ValueObjectInterface
{
    public function __construct(private string $property)
    {
    }

    public static function fromNative(mixed $input): self
    {
        $input = Utils::toArray($input);
        return new self($input['property'] ?? 'missing');
    }

    public function toNative(): array
    {
        return [
            'property' => $this->property
        ];
    }
}
```
An array typehint is unknown what OpenAPI schema can be created.
It can be solved by using CompositeValueObject trait or use the SchemaMethod attribute:

```php
<?php
#[SchemaMethod('provideSchema')]
class ExampleValueObject implements ValueObjectInterface
{
    public function __construct(private string $property)
    {
    }

    public static function fromNative(mixed $input): self
    {
        $input = Utils::toArray($input);
        return new self($input['property'] ?? 'missing');
    }

    public function toNative(): array
    {
        return [
            'property' => $this->property
        ];
    }

    public static function provideSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'property' => ['type' => 'string', 'default' => 'missing']
            ],
            'required' => ['property']
        ]
    }
}
```
