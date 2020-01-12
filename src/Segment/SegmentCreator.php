<?php
declare(strict_types=1);

namespace App\Segment;


use App\Infrastructure\Task\TaskRepository;
use App\Segment\Domain\NewSegment;
use App\Segment\Domain\Segments;
use Symfony\Component\Messenger\MessageBusInterface;

final class SegmentCreator implements \App\Segment\Domain\SegmentCreator
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;
    /**
     * @var Segments
     */
    private Segments $segments;
    /**
     * @var TaskRepository
     */
    private TaskRepository $taskRepository;


    /**
     * SegmentCreator constructor.
     * @param MessageBusInterface $messageBus
     * @param Segments $segments
     * @param TaskRepository $taskRepository
     */
    public function __construct(
        MessageBusInterface $messageBus,
        Segments $segments,
        TaskRepository $taskRepository
    )
    {
        $this->messageBus = $messageBus;
        $this->segments = $segments;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param NewSegment|\App\Segment\Job\NewSegment $newSegment
     */
    public function create(NewSegment $newSegment): void
    {
        if ($newSegment->runAsync === true) {
            $this->messageBus->dispatch($newSegment);
        } else {
            $segment = $newSegment->assemble();
            $this->segments->add($segment);

            $task = $this->taskRepository->findByToken($newSegment->taskToken);
            if ($task !== null) {
                $task->done();
            }
        }
    }
}