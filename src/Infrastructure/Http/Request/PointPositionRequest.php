<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Request;


use App\Domain\Segment\Point;

final class PointPositionRequest
{
    public Point $point;

    /**
     * PointPositionRequest constructor.
     * @param Point $point
     */
    public function __construct(Point $point)
    {
        $this->point = $point;
    }
}