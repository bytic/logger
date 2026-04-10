# Logger

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bytic/logger.svg?style=flat-square)](https://packagist.org/packages/bytic/logger)
[![Latest Stable Version](https://poser.pugx.org/bytic/logger/v/stable)](https://packagist.org/packages/bytic/logger)
[![Latest Unstable Version](https://poser.pugx.org/bytic/logger/v/unstable)](https://packagist.org/packages/bytic/logger)

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Quality Score](https://img.shields.io/scrutinizer/g/bytic/logger.svg?style=flat-square)](https://scrutinizer-ci.com/g/bytic/logger)
[![Total Downloads](https://img.shields.io/packagist/dt/bytic/logger.svg?style=flat-square)](https://packagist.org/packages/bytic/logger)

A PSR-3 logger component for bytic applications, built on top of [Monolog](https://github.com/Seldaek/monolog).

**Requires PHP 8.3+**

---

## Installation

```bash
composer require bytic/logger
```

---

## Usage

### Legacy apps (bytic/container)

Register the `LoggerServiceProvider` in your application bootstrap:

```php
$container->addServiceProvider(new \Nip\Logger\LoggerServiceProvider());
```

The provider registers `log` and `Psr\Log\LoggerInterface` into the container.

> **Deprecation notice:** `LoggerServiceProvider` will be removed in a future major version.
> New applications should use the [Symfony Bundle](#symfony-bundle) integration.

### Symfony Bundle

1. Install `symfony/http-kernel` if it is not already a dependency of your application:
   ```bash
   composer require symfony/http-kernel
   ```

2. Register the bundle in `config/bundles.php`:
   ```php
   return [
       // ...
       \Nip\Logger\ByticLoggerBundle::class => ['all' => true],
   ];
   ```

The bundle registers `Nip\Logger\Manager` and `Psr\Log\LoggerInterface` as Symfony services.

---

## Error Level Routing

PHP native errors (warnings, notices, deprecations) are converted to PSR-3 log
entries by the `bytic/debug` `ErrorHandler`.  Version 2.x uses
`\Nip\Logger\ErrorHandler\PhpErrorLevelMapper` to map each `E_*` constant to
the appropriate PSR-3 level:

| PHP Error Type        | PSR-3 Level |
|-----------------------|-------------|
| `E_WARNING`           | `warning`   |
| `E_USER_WARNING`      | `warning`   |
| `E_CORE_WARNING`      | `warning`   |
| `E_COMPILE_WARNING`   | `warning`   |
| `E_NOTICE`            | `notice`    |
| `E_USER_NOTICE`       | `notice`    |
| `E_DEPRECATED`        | `info`      |
| `E_USER_DEPRECATED`   | `info`      |
| `E_ERROR`             | `error`     |
| `E_USER_ERROR`        | `error`     |
| `E_RECOVERABLE_ERROR` | `error`     |
| `E_PARSE`             | `critical`  |
| `E_CORE_ERROR`        | `critical`  |
| `E_COMPILE_ERROR`     | `critical`  |
| `E_STRICT`            | `debug`     |

This replaces the previous behaviour where warnings and notices were incorrectly
logged at ERROR level.

---

## Migration Guide (Legacy → Symfony)

1. Replace `LoggerServiceProvider` registration with `ByticLoggerBundle`.
2. Inject `Psr\Log\LoggerInterface` via constructor injection instead of
   `$container->get('log')`.
3. Remove any code that reads `$logger->getFormatter()` – the `formatter`
   property and the `getFormatter()` method have been removed.  Message
   formatting is now handled exclusively by Monolog's `LineFormatter`.

---

## Inspiration

<https://github.com/illuminate/log>
