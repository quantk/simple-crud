<?php
declare(strict_types=1);


namespace App\Domain\Segment;


/**
 * Class Point
 * @package App\Domain\Segment
 */
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

    public static function createFromRaw(string $rawPoint): self
    {
        [$x, $y] = explode(',', trim($rawPoint, '()'));
        return static::create((float)$x, (float)$y);
    }

    public function toString(): string
    {
        return "({$this->x},{$this->y})";
    }
}