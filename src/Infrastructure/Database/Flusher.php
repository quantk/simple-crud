<?php
declare(strict_types=1);

namespace App\Infrastructure\Database;


use Doctrine\ORM\EntityManagerInterface;

final class Flusher
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * Flusher constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->entityManager->flush();
    }
}