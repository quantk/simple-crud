<?php
declare(strict_types=1);

namespace App\Infrastructure\Task;


use App\Domain\Segment\Segment;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\ParameterType;

final class TaskRepository
{
    /**
     * @var Connection
     */
    private Connection $connection;


    /**
     * SectionRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Task $task
     * @throws DBALException
     */
    public function save(Task $task): void
    {
        $statement = $this->connection->prepare('
                        INSERT INTO tasks(id, token, status, created_at, message) 
                        VALUES (:id, :token, :status, :created_at, :message)
                        ON CONFLICT (id) DO UPDATE SET token=:token, status=:status, created_at=:created_at, message=:message
        ');
        $statement->bindValue('id', $task->id, ParameterType::STRING);
        $statement->bindValue('token', $task->token, ParameterType::STRING);
        $statement->bindValue('status', $task->status->value, ParameterType::STRING);
        $statement->bindValue('created_at', $task->createdAt->format('Y-m-d H:i:s'), ParameterType::STRING);
        $statement->bindValue('message', $task->message, ParameterType::STRING);
        $statement->execute();
    }

    /**
     * @param string $token
     * @return Segment|null
     * @throws DBALException
     */
    public function findByToken(string $token): ?Task
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM tasks WHERE token = ? LIMIT 1'
        );
        $statement->bindValue(1, $token);

        $statement->execute();

        $rawTask = $statement->fetch(FetchMode::ASSOCIATIVE);

        if ($rawTask === false) {
            return null;
        }

        return $this->hydrate($rawTask);
    }

    private function hydrate(array $rawTask)
    {
        return Task::create($rawTask['id'], $rawTask['token'], TaskStatus::create($rawTask['status']), $rawTask['message'] ?? null);
    }
}