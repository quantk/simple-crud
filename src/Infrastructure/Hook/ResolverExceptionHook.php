<?php
declare(strict_types=1);

namespace App\Infrastructure\Hook;


use App\Infrastructure\Http\Controller\Resolver\ResolverError;
use App\Infrastructure\Http\Response\Responder;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ResolverExceptionHook
{
    /**
     * @var Responder
     */
    private Responder $responder;


    /**
     * ResolverExceptionHook constructor.
     * @param Responder $responder
     */
    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof ResolverError) {
            $response = $this->responder->error($exception->toArray());
            $event->setResponse($response);
        }
    }
}