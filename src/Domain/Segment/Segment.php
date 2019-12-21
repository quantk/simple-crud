<?php
declare(strict_types=1);


namespace App\Domain\Segment;


/**
 * Class Segment
 * @package App\Domain\Segment
 * @property-read string $uid
 * @property-read Point $leftSide
 * @property-read Point $rightSide
 * @property-read \DateTimeImmutable $createdAt
 */
final class Segment
{
    public const UP_POSITION = 'up';
    public const DOWN_POSITION = 'down';
    public const CUT_POSITION = 'cut';

    public string $uid;
    public Point $leftSide;
    public Point $rightSide;
    public \DateTimeImmutable $createdAt;

    private function __construct(string $uid, Point $leftSide, Point $rightSide)
    {
        $this->uid = $uid;
        $this->leftSide = $leftSide;
        $this->rightSide = $rightSide;
        $this->createdAt = new \DateTimeImmutable();
    }

    public static function create(string $uid, Point $leftSide, Point $rightSide): self
    {
        return new static($uid, $leftSide, $rightSide);
    }

    public function calculatePointPositionByVertical(Point $point): string
    {
        $segment = $this;

        $k = ($segment->leftSide->y - $segment->rightSide->y) / ($segment->leftSide->x - $segment->rightSide->x);
        $b = $segment->rightSide->y - $k * $segment->rightSide->x;

        $yPosition = $k * $point->x + $b;

        if ($yPosition < $point->y) {
            return self::UP_POSITION;
        }

        if ($yPosition > $point->y) {
            return self::DOWN_POSITION;
        }

        return self::CUT_POSITION;
    }
}