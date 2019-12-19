<?php
declare(strict_types=1);


namespace App\Domain\Section;


final class Point
{
    public float $x;

    public float $y;

    private function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public static function create(float $x, float $y): self
    {
        return new static($x, $y);
    }
}