<?php
declare(strict_types=1);

namespace App\Infrastructure\Task;

/**
 * Class TaskStatus
 * @package App\Infrastructure\Task
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
}