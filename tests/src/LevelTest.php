<?php

declare(strict_types=1);

namespace Nip\Logger\Tests;

use Monolog\Level as MonologLevel;
use Nip\Logger\Level;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/**
 * @covers \Nip\Logger\Level
 */
class LevelTest extends TestCase
{
    /**
     * @param array{string, Level} $args
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('providePsrLevels')]
    public function test_fromPsrLevel_round_trips(string $psrLevel, Level $expected): void
    {
        self::assertSame($expected, Level::fromPsrLevel($psrLevel));
        self::assertSame($psrLevel, $expected->toPsrLevel());
    }

    /**
     * @return array<string, array{string, Level}>
     */
    public static function providePsrLevels(): array
    {
        return [
            'debug'     => [LogLevel::DEBUG,     Level::Debug],
            'info'      => [LogLevel::INFO,       Level::Info],
            'notice'    => [LogLevel::NOTICE,     Level::Notice],
            'warning'   => [LogLevel::WARNING,    Level::Warning],
            'error'     => [LogLevel::ERROR,      Level::Error],
            'critical'  => [LogLevel::CRITICAL,   Level::Critical],
            'alert'     => [LogLevel::ALERT,      Level::Alert],
            'emergency' => [LogLevel::EMERGENCY,  Level::Emergency],
        ];
    }

    public function test_fromPsrLevel_is_case_insensitive(): void
    {
        self::assertSame(Level::Warning, Level::fromPsrLevel('WARNING'));
        self::assertSame(Level::Warning, Level::fromPsrLevel('Warning'));
    }

    public function test_fromPsrLevel_throws_on_invalid_level(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Level::fromPsrLevel('not-a-level');
    }

    public function test_toMonologLevel_returns_correct_enum(): void
    {
        self::assertSame(MonologLevel::Warning, Level::Warning->toMonologLevel());
        self::assertSame(MonologLevel::Error, Level::Error->toMonologLevel());
        self::assertSame(MonologLevel::Debug, Level::Debug->toMonologLevel());
    }

    public function test_values_match_monolog_integers(): void
    {
        self::assertSame(100, Level::Debug->value);
        self::assertSame(200, Level::Info->value);
        self::assertSame(250, Level::Notice->value);
        self::assertSame(300, Level::Warning->value);
        self::assertSame(400, Level::Error->value);
        self::assertSame(500, Level::Critical->value);
        self::assertSame(550, Level::Alert->value);
        self::assertSame(600, Level::Emergency->value);
    }
}
