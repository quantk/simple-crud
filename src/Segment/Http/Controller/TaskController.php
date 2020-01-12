<?php
declare(strict_types=1);

namespace App\Segment\Http\Controller;


use App\Infrastructure\Http\Response\Responder;
use App\Segment\Task\TaskRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TaskController
 * @package App\Infrastructure\Http\Controller
 * @Route("/tasks")
 */
final class TaskController
{
    /**
     * @var Responder
     */
    private Responder $responder;

    /**
     * TaskController constructor.
     * @param Responder $responder
     */
    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * @param string $token
     * @param TaskRepository $taskRepository
     * @Route("/{token}", name="tasks_token_status", methods={"GET"})
     * @return Response
     */
    public function status(
        string $token,
        TaskRepository $taskRepository
    )
    {
        $task = $taskRepository->findByToken($token);

        if ($task === null) {
            throw new NotFoundHttpException();
        }

        return $this->responder->item($task->toArray());
    }
}