<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>apie-phpstan-rules</h1>






 [![Latest Stable Version](http://poser.pugx.org/apie/apie-phpstan-rules/v)](https://packagist.org/packages/apie/apie-phpstan-rules) [![Total Downloads](http://poser.pugx.org/apie/apie-phpstan-rules/downloads)](https://packagist.org/packages/apie/apie-phpstan-rules) [![Latest Unstable Version](http://poser.pugx.org/apie/apie-phpstan-rules/v/unstable)](https://packagist.org/packages/apie/apie-phpstan-rules) [![License](http://poser.pugx.org/apie/apie-phpstan-rules/license)](https://packagist.org/packages/apie/apie-phpstan-rules) [![PHP Version Require](http://poser.pugx.org/apie/apie-phpstan-rules/require/php)](https://packagist.org/packages/apie/apie-phpstan-rules) [![Code coverage](https://raw.githubusercontent.com/apie-lib/apie-phpstan-rules/main/coverage_badge.svg)](https://apie-lib.github.io/coverage/apie-phpstan-rules/index.html)  

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
