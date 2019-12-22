<?php
declare(strict_types=1);

namespace App\Infrastructure\Job;


use App\Domain\Segment\Contract\Segments;
use App\Domain\Segment\Segment;
use App\Infrastructure\Task\TaskRepository;
use Doctrine\DBAL\DBALException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateSegmentJob implements MessageHandlerInterface
{
    private Segments $segmentRepository;
    /**
     * @var TaskRepository
     */
    private TaskRepository $taskRepository;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(
        Segments $segmentRepository,
        TaskRepository $taskRepository,
        LoggerInterface $logger
    )
    {
        $this->segmentRepository = $segmentRepository;
        $this->taskRepository = $taskRepository;
        $this->logger = $logger;
    }

    /**
     * @param Segment $segment
     * @throws DBALException
     * @throws \Throwable
     */
    public function __invoke(Segment $segment)
    {
        $this->logger->debug('Executing CreateSegmentJob');
        $task = $this->taskRepository->findByToken($segment->uid);

        if ($task === null) {
            throw new \RuntimeException("Task not found for segment[{$segment->uid}]");
        }

        try {
            $task = $task->execute();
            $this->taskRepository->save($task);

            $this->segmentRepository->add($segment);

            $task = $task->done();
            $this->taskRepository->save($task);
            $this->logger->debug('CreateSegmentJob done');
        } catch (\Throwable $e) {
            $task = $task->error($e->getMessage());
            $this->taskRepository->save($task);
            $this->logger->error("CreateSegmentJob error. Message: {$e->getMessage()}");
            throw $e;
        }
    }
}