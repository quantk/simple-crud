<?php
declare(strict_types=1);

namespace App\Infrastructure\Task;


/**
 * Class Task
 * @package App\Infrastructure\Task
 * @property-read string $id
 * @property-read string $token
 * @property-read TaskStatus $status
 * @property-read ?string $message
 * @property-read \DateTimeImmutable $createdAt
 */
final class Task
{
    public string $id;
    public string $token;
    public TaskStatus $status;
    public ?string $message;
    public \DateTimeImmutable $createdAt;

    /**
     * Task constructor.
     * @param string $id
     * @param string $token
     * @param TaskStatus $status
     * @param string $message
     */
    private function __construct(string $id, string $token, TaskStatus $status, ?string $message)
    {
        $this->id = $id;
        $this->token = $token;
        $this->status = $status;
        $this->message = $message;
        $this->createdAt = new \DateTimeImmutable();
    }

    public static function create(string $id, string $token, TaskStatus $status, ?string $message = null): self
    {
        return new static($id, $token, $status, $message);
    }

    public function execute(): self
    {
        $newTask = clone $this;
        $newTask->status = TaskStatus::executing();

        return $newTask;
    }

    public function done(): self
    {
        $newTask = clone $this;
        $newTask->status = TaskStatus::done();

        return $newTask;
    }

    public function error(string $message): self
    {
        $newTask = clone $this;
        $newTask->status = TaskStatus::error();
        $newTask->message = $message;

        return $newTask;
    }
}