<?php
declare(strict_types=1);

namespace App\Segment\Task;


use App\Segment\Domain\Segment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

final class TaskRepository extends ServiceEntityRepository
{
    /**
     * SectionRepository constructor.
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Task::class);
    }

    /**
     * @param Task $task
     * @throws ORMException
     */
    public function add(Task $task): void
    {
        $this->getEntityManager()->persist($task);
    }

    /**
     * @param string $token
     * @return Segment|null
     */
    public function findByToken(string $token): ?Task
    {
        /** @var Task $task */
        $task = $this->findOneBy(['token' => $token]);
        return $task;
    }
}