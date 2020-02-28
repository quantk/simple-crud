<?php
declare(strict_types=1);


namespace App\Segment\Domain;


/**
 * Class Point
 * @package App\Domain\Segment
 */
final class Point
{
    public const UP_POSITION = 'up';
    public const DOWN_POSITION = 'down';
    public const CUT_POSITION = 'cut';

    private float $x;

    private float $y;

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

    public function toArray()
    {
        return [
            'x' => $this->x,
            'y' => $this->y
        ];
    }

    public function calculateVerticalPosition(Point $leftSide, Point $rightSide): float
    {
        $k = ($leftSide->y - $rightSide->y) / ($leftSide->x - $rightSide->x);
        $b = $rightSide->y - $k * $rightSide->x;

        return $k * $this->x + $b;
    }

    public function getPositionByVerticalCoordinate(float $verticalCoordinate)
    {
        if ($verticalCoordinate < $this->y) {
            return self::UP_POSITION;
        }

        if ($verticalCoordinate > $this->y) {
            return self::DOWN_POSITION;
        }

        return self::CUT_POSITION;
    }
}