<?php
declare(strict_types=1);

namespace App\Segment\Http\Controller;


use App\Infrastructure\Database\Contract\UidGenerator;
use App\Infrastructure\Database\Flusher;
use App\Infrastructure\Http\Response\Responder;
use App\Infrastructure\Task\Task;
use App\Infrastructure\Task\TaskRepository;
use App\Segment\Domain\Segment;
use App\Segment\Domain\SegmentCreator;
use App\Segment\Domain\Segments;
use App\Segment\Http\Request\CreateSegmentRequest;
use App\Segment\Http\Request\PointPositionRequest;
use App\Segment\Job\NewSegment;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SegmentController
 * @package App\Infrastructure\Http\Controller
 * @Route("/segments")
 */
final class SegmentController
{
    /**
     * @var Responder
     */
    private Responder $responder;
    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * SegmentController constructor.
     * @param Responder $responder
     * @param Flusher $flusher
     */
    public function __construct(
        Responder $responder,
        Flusher $flusher
    )
    {
        $this->responder = $responder;
        $this->flusher = $flusher;
    }

    /**
     * @param Segments $segmentRepository
     * @return Response
     * @Route("/list", name="segment_list", methods={"GET"})
     */
    public function list(
        Segments $segmentRepository
    )
    {
        $segments = array_map(function (Segment $segment) {
            return $segment->toArray();
        }, $segmentRepository->all());
        return $this->responder->collection($segments);
    }

    /**
     * @param CreateSegmentRequest $request
     * @param UidGenerator $generator
     * @param TaskRepository $taskRepository
     * @param SegmentCreator $creator
     * @return Response
     * @throws ORMException
     * @Route("/create", name="segment_create", methods={"POST"})
     */
    public function create(
        CreateSegmentRequest $request,
        UidGenerator $generator,
        TaskRepository $taskRepository,
        SegmentCreator $creator
    )
    {
        $newSegmentId = $generator->generate();

        $newSegment = new NewSegment($newSegmentId, $request->leftSide, $request->rightSide, $request->runAsync);

        $task = Task::createIdle($generator->generate(), $newSegmentId);
        $taskRepository->add($task);

        $creator->create($newSegment);

        $this->flusher->flush();

        return $this->responder->item([
            'segment_id' => $newSegmentId,
            'task' => $task ? $task->toArray() : null
        ]);
    }

    /**
     * @param string $uid
     * @param Segments $segmentRepository
     * @return Response
     * @Route("/{uid}/remove", name="segments_remove", methods={"POST"})
     */
    public function remove(
        string $uid,
        Segments $segmentRepository
    )
    {
        $segmentRepository->remove($uid);

        $this->flusher->flush();
        return $this->responder->emptyResponse(201);
    }

    /**
     * @param string $uid
     * @param Segments $segmentRepository
     * @return Response
     * @Route("/{uid}", name="segment_item", methods={"GET"})
     */
    public function item(
        string $uid,
        Segments $segmentRepository
    )
    {
        $segment = $segmentRepository->findSegment($uid);
        if ($segment === null) {
            throw new NotFoundHttpException();
        }

        return $this->responder->item($segment->toArray());
    }

    /**
     * @param string $uid
     * @param Segments $segmentRepository
     * @param PointPositionRequest $request
     * @return Response
     * @Route("/{uid}/point_position", name="segments_point_position", methods={"GET"})
     */
    public function pointPosition(
        string $uid,
        Segments $segmentRepository,
        PointPositionRequest $request
    )
    {
        $segment = $segmentRepository->findSegment($uid);
        if ($segment === null) {
            throw new NotFoundHttpException();
        }

        $position = $segment->calculatePointPositionByVertical($request->point);

        return $this->responder->item([
            'position' => $position
        ]);
    }
}