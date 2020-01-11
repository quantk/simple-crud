<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Controller\Resolver;


use App\Domain\Segment\Point;
use App\Infrastructure\Http\Request\PointPositionRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class PointPositionRequestResolver implements ArgumentValueResolverInterface
{

    /**
     * @inheritDoc
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return PointPositionRequest::class === $argument->getType();
    }

    /**
     * @inheritDoc
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $x1 = $request->query->get('x1');
        $y1 = $request->query->get('y1');

        yield new PointPositionRequest(Point::create($x1, $y1));
    }
}