<?php
declare(strict_types=1);

namespace App\Segment\Http\Request;

use App\Segment\Domain\Point;

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