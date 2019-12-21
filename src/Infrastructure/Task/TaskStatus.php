<?php
declare(strict_types=1);

namespace App\Infrastructure\Task;

/**
 * Class TaskStatus
 * @package App\Infrastructure\Task
 * @property-read string $value
 */
final class TaskStatus
{
    public const STATUS_IDLE = 'idle';
    public const STATUS_EXECUTING = 'executing';
    public const STATUS_DONE = 'done';
    public const STATUS_ERROR = 'error';

    public const AVAILABLE_STATUSES = [
        self::STATUS_DONE,
        self::STATUS_IDLE,
        self::STATUS_EXECUTING,
        self::STATUS_ERROR,
    ];

    public string $value;

    /**
     * TaskStatus constructor.
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function create(string $status)
    {
        if (in_array($status, self::AVAILABLE_STATUSES, true) === false) {
            throw new \RuntimeException('Invalid status');
        }

        return new static($status);
    }

    public static function idle(): self
    {
        return new static(self::STATUS_IDLE);
    }

    public static function executing(): self
    {
        return new static(self::STATUS_EXECUTING);
    }

    public static function done(): self
    {
        return new static(self::STATUS_DONE);
    }

    public static function error(): self
    {
        return new static(self::STATUS_ERROR);
    }
}