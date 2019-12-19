<?php
declare(strict_types=1);


namespace App\Domain\Section;


final class Segment
{
    public string $uid;
    public Point $leftSide;
    public Point $rightSide;

    private function __construct(string $uid, Point $leftSide, Point $rightSide)
    {
        $this->uid = $uid;
        $this->leftSide = $leftSide;
        $this->rightSide = $rightSide;
    }

    public static function create(string $uid, Point $leftSide, Point $rightSide): self
    {
        return new static($uid, $leftSide, $rightSide);
    }
}