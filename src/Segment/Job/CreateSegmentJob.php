<?php
declare(strict_types=1);

namespace App\Segment\Job;


use App\Infrastructure\Database\Flusher;
use App\Segment\Domain\Segment;
use App\Segment\Domain\Segments;
use App\Segment\Task\TaskRepository;
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
    /**
     * @var Flusher
     */
    private Flusher $flusher;

    public function __construct(
        Segments $segmentRepository,
        TaskRepository $taskRepository,
        LoggerInterface $logger,
        Flusher $flusher
    )
    {
        $this->segmentRepository = $segmentRepository;
        $this->taskRepository = $taskRepository;
        $this->logger = $logger;
        $this->flusher = $flusher;
    }

    /**
     * @param Segment $segment
     * @throws \Throwable
     */
    public function __invoke(Segment $segment)
    {
        $this->logger->debug('Executing CreateSegmentJob');
        $task = $this->taskRepository->findByToken($segment->getId());

        if ($task === null) {
            throw new \RuntimeException("Task not found for segment[{$segment->getId()}]");
        }

        try {
            $task->execute();
            $this->flusher->flush();

            $this->segmentRepository->add($segment);

            $task->done();
            $this->flusher->flush();
            $this->logger->debug('CreateSegmentJob done');
        } catch (\Throwable $e) {
            $task->error($e->getMessage());
            $this->flusher->flush();
            $this->logger->error("CreateSegmentJob error. Message: {$e->getMessage()}");
            throw $e;
        }
    }
}