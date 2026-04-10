<?php

declare(strict_types=1);

namespace Nip\Logger\Tests;

use Monolog\Handler\TestHandler;
use Monolog\Level as MonologLevel;
use Monolog\Logger as Monolog;
use Nip\Logger\Logger;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nip\Logger\Logger
 */
class LoggerTest extends TestCase
{
    private function makeLogger(): array
    {
        $handler = new TestHandler();
        $monolog = new Monolog('test', [$handler]);
        $logger = new Logger($monolog);

        return [$logger, $handler];
    }

    public function test_log_passes_raw_message_without_timestamp_prefix(): void
    {
        [$logger, $handler] = $this->makeLogger();

        $logger->warning('Something went wrong');

        $records = $handler->getRecords();
        self::assertCount(1, $records);

        $message = $records[0]->message;

        // The message must NOT start with a timestamp or level prefix –
        // that is the job of Monolog's formatter, not this wrapper.
        self::assertSame('Something went wrong', $message);
        self::assertStringNotContainsString('[warning]', $message);
        self::assertStringNotContainsString('[error]', $message);
        self::assertDoesNotMatchRegularExpression('/^\d{4}-\d{2}-\d{2}T/', $message);
    }

    public function test_log_interpolates_context_placeholders(): void
    {
        [$logger, $handler] = $this->makeLogger();

        $logger->info('Hello {user}', ['user' => 'Alice']);

        self::assertSame('Hello Alice', $handler->getRecords()[0]->message);
    }

    public function test_log_leaves_message_unchanged_when_no_placeholders(): void
    {
        [$logger, $handler] = $this->makeLogger();

        $logger->error('No placeholders here');

        self::assertSame('No placeholders here', $handler->getRecords()[0]->message);
    }

    public function test_log_interpolates_datetime_context(): void
    {
        [$logger, $handler] = $this->makeLogger();

        $dt = new \DateTimeImmutable('2024-01-15T10:00:00+00:00');
        $logger->debug('Event at {time}', ['time' => $dt]);

        self::assertSame('Event at 2024-01-15T10:00:00+00:00', $handler->getRecords()[0]->message);
    }

    public function test_log_interpolates_object_with_to_string(): void
    {
        [$logger, $handler] = $this->makeLogger();

        $obj = new class {
            public function __toString(): string
            {
                return 'my-object';
            }
        };
        $logger->notice('Object: {obj}', ['obj' => $obj]);

        self::assertSame('Object: my-object', $handler->getRecords()[0]->message);
    }

    public function test_log_interpolates_plain_object_with_class_name(): void
    {
        [$logger, $handler] = $this->makeLogger();

        $logger->notice('Object: {obj}', ['obj' => new \stdClass()]);

        self::assertSame('Object: [object stdClass]', $handler->getRecords()[0]->message);
    }

    public function test_get_logger_returns_inner_logger(): void
    {
        $handler = new TestHandler();
        $monolog = new Monolog('test', [$handler]);
        $logger = new Logger($monolog);

        self::assertSame($monolog, $logger->getLogger());
    }

    public function test_log_uses_correct_monolog_level(): void
    {
        [$logger, $handler] = $this->makeLogger();

        $logger->warning('test warning');

        $records = $handler->getRecords();
        self::assertCount(1, $records);
        self::assertSame(MonologLevel::Warning, $records[0]->level);
    }

    public function test_call_proxies_to_inner_logger(): void
    {
        [$logger, $handler] = $this->makeLogger();

        // Call debug() which is defined on PSR AbstractLogger but ultimately
        // proxies through to Monolog
        $logger->debug('Proxied message');

        self::assertTrue($handler->hasDebug('Proxied message'));
    }
}

