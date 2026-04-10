<?php

declare(strict_types=1);

namespace Nip\Logger\Tests\ErrorHandler;

use Nip\Logger\ErrorHandler\PhpErrorLevelMapper;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/**
 * @covers \Nip\Logger\ErrorHandler\PhpErrorLevelMapper
 */
class PhpErrorLevelMapperTest extends TestCase
{
    public function test_getLevelMap_returns_array(): void
    {
        $map = PhpErrorLevelMapper::getLevelMap();

        self::assertIsArray($map);
        self::assertNotEmpty($map);
    }

    /**
     * @param array{int, string} $args
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideErrorLevelMappings')]
    public function test_toLogLevel_maps_correctly(int $errorType, string $expectedLevel): void
    {
        self::assertSame($expectedLevel, PhpErrorLevelMapper::toLogLevel($errorType));
    }

    /**
     * @return array<string, array{int, string}>
     */
    public static function provideErrorLevelMappings(): array
    {
        return [
            'E_WARNING maps to WARNING'         => [\E_WARNING,          LogLevel::WARNING],
            'E_USER_WARNING maps to WARNING'    => [\E_USER_WARNING,     LogLevel::WARNING],
            'E_CORE_WARNING maps to WARNING'    => [\E_CORE_WARNING,     LogLevel::WARNING],
            'E_NOTICE maps to NOTICE'           => [\E_NOTICE,           LogLevel::NOTICE],
            'E_USER_NOTICE maps to NOTICE'      => [\E_USER_NOTICE,      LogLevel::NOTICE],
            'E_DEPRECATED maps to INFO'         => [\E_DEPRECATED,       LogLevel::INFO],
            'E_USER_DEPRECATED maps to INFO'    => [\E_USER_DEPRECATED,  LogLevel::INFO],
            'E_ERROR maps to ERROR'             => [\E_ERROR,            LogLevel::ERROR],
            'E_USER_ERROR maps to ERROR'        => [\E_USER_ERROR,       LogLevel::ERROR],
            'E_RECOVERABLE_ERROR maps to ERROR' => [\E_RECOVERABLE_ERROR, LogLevel::ERROR],
            'E_PARSE maps to CRITICAL'          => [\E_PARSE,            LogLevel::CRITICAL],
            'E_CORE_ERROR maps to CRITICAL'     => [\E_CORE_ERROR,       LogLevel::CRITICAL],
            'E_COMPILE_ERROR maps to CRITICAL'  => [\E_COMPILE_ERROR,    LogLevel::CRITICAL],
            'E_COMPILE_WARNING maps to WARNING' => [\E_COMPILE_WARNING,  LogLevel::WARNING],
            'E_STRICT maps to DEBUG'            => [\E_STRICT,           LogLevel::DEBUG],
        ];
    }

    public function test_toLogLevel_returns_error_for_unknown_type(): void
    {
        self::assertSame(LogLevel::ERROR, PhpErrorLevelMapper::toLogLevel(0));
    }

    public function test_getLevelMap_keys_are_php_error_constants(): void
    {
        $map = PhpErrorLevelMapper::getLevelMap();

        $knownConstants = [
            \E_NOTICE, \E_USER_NOTICE, \E_WARNING, \E_CORE_WARNING, \E_USER_WARNING,
            \E_ERROR, \E_USER_ERROR, \E_CORE_ERROR, \E_RECOVERABLE_ERROR, \E_PARSE,
            \E_COMPILE_ERROR, \E_COMPILE_WARNING, \E_STRICT, \E_DEPRECATED, \E_USER_DEPRECATED,
        ];

        foreach ($knownConstants as $constant) {
            self::assertArrayHasKey($constant, $map, "E_* constant {$constant} is missing from the level map.");
        }
    }
}
