<?php
declare(strict_types=1);

namespace App\Segment\Http\Request;

use App\Segment\Domain\Point;

final class CreateSegmentRequest
{
    public Point $leftSide;
    public Point $rightSide;
    public bool $runAsync;

    /**
     * CreateSegmentRequest constructor.
     * @param Point $leftSide
     * @param Point $rightSide
     * @param bool $runAsync
     */
    public function __construct(Point $leftSide, Point $rightSide, bool $runAsync)
    {
        $this->leftSide = $leftSide;
        $this->rightSide = $rightSide;
        $this->runAsync = $runAsync;
    }
}