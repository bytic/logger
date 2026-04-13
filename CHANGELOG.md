# Changelog
All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 2.0.0 (unreleased)

### Added
- **PHP 8.3+ requirement** – minimum supported PHP version raised from `^7.0|^8.0` to `^8.3`.
- **`Nip\Logger\Level` enum** – replaces the plain `$levels` array in `HasLevelsTrait`.  Use `Level::fromPsrLevel('warning')` or `Level::Warning->toMonologLevel()` in new code.
- **`Nip\Logger\ErrorHandler\PhpErrorLevelMapper`** – maps PHP `E_*` constants to accurate PSR-3 log levels (warnings → `warning`, notices → `notice`, deprecations → `info`).  Previously all PHP errors were logged at `ERROR` level.
- **`Nip\Logger\ErrorHandler\LoggerErrorHandler`** – wires a PSR-3 logger into `Nip\Debug\ErrorHandler` with per-level severity via `PhpErrorLevelMapper`.
- `declare(strict_types=1)` added to all source files.
- Full return type declarations and typed properties throughout `src/`.
- `#[\Override]` attribute on all interface/abstract method implementations.

### Changed
- **`Logger::log()` no longer prepends a timestamp+level prefix to the message.**  The old `Logger::format()` method produced output like `2024-01-01T00:00:00+00:00 [error] message\n` as the _message body_, causing Monolog to double-wrap it with its own timestamp.  The method has been removed; only PSR-3 `{placeholder}` interpolation is performed now.
- **`Manager::$dateFormat`** typed as `protected string`.
- **`HasLevelsTrait::$levels`** typed as `protected array`.
- **`HasChannels::$channels`** typed as `protected array`.
- **`HasLevelsTrait::$errorLevelMap`** – corrected `E_ERROR` mapping from `WARNING` (wrong) to `ERROR`.
- **`HasChannels::getFallbackChannelName()`** – now reads the environment name from the container's `env` binding (falls back to `'production'`).
- **`MonologWrappers::prepareHandler()`** – removed dead Monolog API 1/2 version branches; always targets Monolog 3 (`FormattableHandlerInterface`).  Fixed `$this->app->make()` call (undefined) to `$this->getContainer()->make()`.
- **`LoggerServiceProvider::boot()`** – now calls `LoggerErrorHandler::register()` instead of `setDefaultLogger()` without level mapping.
- Monolog constraint narrowed from `^2.0|^3.0` to `^3.0`.
- Added `symfony/deprecation-contracts ^3.0` to `require`.

### Deprecated
- `Nip\Logger\Manager\HasApplication::getApplication()` and `setApplication()` – these methods are unused; inject services via the container.
- `Nip\Logger\Manager\HasLevelsTrait::$errorLevelMap` static property – use `PhpErrorLevelMapper::getLevelMap()` instead.
- `Nip_PHPException::log()` – use a PSR-3 logger instead.

### Removed
- `Logger::$formatter` property (was `protected $formatter`).
- `Logger::getFormatter()` method.
- `Logger::format()` method (formatting moved to Monolog's `LineFormatter`).

### Fixed
- PHP warnings/notices/deprecations no longer appear in logs as `ERROR`.  They are now correctly mapped to `WARNING`, `NOTICE`, and `INFO` respectively.
- `MonologWrappers::createMonologDriver()` no longer calls the undefined `$this->app` property.
- `CreateDrivers::callCustomCreator()` no longer calls the undefined global `app()` helper.

---

## 0.0.3

### Changed
- Make Nip_Collection extend Nip\Collection

## 0.0.2

### Added
- AutoLoaderServiceProvider
- DispatcherServiceProvider
- RouterServiceProvider

### Changed
- Make Request extend Symfony Request

### Deprecated
- rename all Autoloader -> AutoLoader.
- rename Application dispatch() -> handleRequest().
- rename Application preDispatch() -> preHandleRequest().
- rename Application postDispatch() -> postDispatch().

### Removed
- FrontController

### Fixed
- Nothing.
