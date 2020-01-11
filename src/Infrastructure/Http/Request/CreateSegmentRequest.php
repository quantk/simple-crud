<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Request;


use App\Domain\Segment\Point;


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