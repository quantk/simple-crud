<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;


use App\Domain\Segment\Contract\Segments;
use App\Domain\Segment\Point;
use App\Domain\Segment\Segment;
use App\Infrastructure\Database\Contract\UidGenerator;
use App\Infrastructure\Database\Flusher;
use App\Infrastructure\Http\Response\Responder;
use App\Infrastructure\Task\Task;
use App\Infrastructure\Task\TaskRepository;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
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
     * @param Request $request
     * @param Segments $segmentRepository
     * @param UidGenerator $generator
     * @param MessageBusInterface $messageBus
     * @param TaskRepository $taskRepository
     * @return Response
     * @throws ORMException
     * @Route("/create", name="segment_create", methods={"POST"})
     */
    public function create(
        Request $request,
        Segments $segmentRepository,
        UidGenerator $generator,
        MessageBusInterface $messageBus,
        TaskRepository $taskRepository
    )
    {
        $leftSideRaw = $request->request->get('left_side');
        $rightSideRaw = $request->request->get('right_side');

        $leftSide = Point::create((float)$leftSideRaw['x'], (float)$leftSideRaw['y']);
        $rightSide = Point::create((float)$rightSideRaw['x'], (float)$rightSideRaw['y']);

        $needToRunAsync = $request->request->getBoolean('run_async', false);

        $uid = $generator->generate();

        $segment = Segment::create(
            $uid,
            $leftSide,
            $rightSide
        );

        $task = null;

        if ($needToRunAsync === true) {
            $task = Task::createIdle($generator->generate(), $segment->getId());
            $taskRepository->add($task);
            $this->flusher->flush();
            $messageBus->dispatch($segment);
        } else {
            $segmentRepository->add($segment);
        }

        $this->flusher->flush();

        return $this->responder->item([
            'segment' => $segment->toArray(),
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
     * @param Request $request
     * @Route("/{uid}/point_position", name="segments_point_position", methods={"GET"})
     * @return Response
     */
    public function pointPosition(
        string $uid,
        Segments $segmentRepository,
        Request $request
    )
    {
        $segment = $segmentRepository->findSegment($uid);
        if ($segment === null) {
            throw new NotFoundHttpException();
        }

        $x1 = $request->get('x1');
        $y1 = $request->get('y1');

        if ($x1 === null || $y1 === null) {
            return $this->responder->error(['Point is required'], 400);
        }

        $point = Point::create((float)$x1, (float)$y1);

        $position = $segment->calculatePointPositionByVertical($point);

        return $this->responder->item([
            'position' => $position
        ]);
    }
}