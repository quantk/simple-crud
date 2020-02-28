<?php
declare(strict_types=1);

namespace App\Infrastructure\Task;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class Task
 * @package App\Infrastructure\Task
 * @property-read string $id
 * @property-read string $token
 * @property-read TaskStatus $status
 * @property-read ?string $message
 * @property-read \DateTimeImmutable $createdAt
 * @ORM\Entity(repositoryClass="App\Infrastructure\Task\TaskRepository")
 */
class Task
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private string $id;
    /**
     * @var string
     * @ORM\Column(type="uuid", nullable=false, unique=true)
     */
    private string $token;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private string $status;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $message;
    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * Task constructor.
     * @param string $id
     * @param string $token
     * @param string $status
     * @param string $message
     * @throws \Exception
     */
    private function __construct(string $id, string $token, string $status, ?string $message)
    {
        $this->id = $id;
        $this->token = $token;
        $this->status = $status;
        $this->message = $message;
        $this->createdAt = new \DateTimeImmutable();
    }

    public static function create(string $id, string $token, string $status, ?string $message = null): self
    {
        return new static($id, $token, $status, $message);
    }

    public static function createIdle(string $id, string $token): self
    {
        return new static($id, $token, TaskStatus::STATUS_IDLE, null);
    }

    public function execute(): void
    {
        $this->status = TaskStatus::STATUS_EXECUTING;
    }

    public function done(): void
    {
        $this->status = TaskStatus::STATUS_DONE;
    }

    public function error(string $message): void
    {
        $this->status = TaskStatus::STATUS_ERROR;
        $this->message = $message;
    }

    public function toArray()
    {
        return [
            'token' => $this->token,
            'status' => $this->status,
            'message' => $this->message,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s')
        ];
    }
}