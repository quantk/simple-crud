<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Controller\Resolver;


use App\Domain\Segment\Point;
use App\Infrastructure\Http\Request\CreateSegmentRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class CreateSegmentRequestResolver implements ArgumentValueResolverInterface
{
    /**
     * @inheritDoc
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return CreateSegmentRequest::class === $argument->getType();
    }

    /**
     * @inheritDoc
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $leftSideRaw = $request->request->get('left_side');
        $rightSideRaw = $request->request->get('right_side');

        $leftSide = Point::create((float)$leftSideRaw['x'], (float)$leftSideRaw['y']);
        $rightSide = Point::create((float)$rightSideRaw['x'], (float)$rightSideRaw['y']);

        $needToRunAsync = $request->request->getBoolean('run_async', false);

        yield new CreateSegmentRequest($leftSide, $rightSide, $needToRunAsync);
    }
}