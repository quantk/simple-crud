<?php
declare(strict_types=1);

namespace App\Segment\Job;


use App\Segment\Domain\Point;
use App\Segment\Domain\Segment;

final class NewSegment implements \App\Segment\Domain\NewSegment
{
    public string $taskToken;
    public Point $leftSide;
    public Point $rightSide;
    public bool $runAsync;

    /**
     * NewSegment constructor.
     * @param string $taskToken
     * @param Point $leftSide
     * @param Point $rightSide
     * @param bool $runAsync
     */
    public function __construct(string $taskToken, Point $leftSide, Point $rightSide, bool $runAsync)
    {
        $this->taskToken = $taskToken;
        $this->leftSide = $leftSide;
        $this->rightSide = $rightSide;
        $this->runAsync = $runAsync;
    }

    public function assemble(): Segment
    {
        return Segment::create($this->taskToken, $this->leftSide, $this->rightSide);
    }
}