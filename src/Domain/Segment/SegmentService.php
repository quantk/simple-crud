<?php
declare(strict_types=1);

namespace App\Domain\Segment;


final class SegmentService
{
    public const UP_POSITION = 'up';
    public const DOWN_POSITION = 'down';
    public const CUT_POSITION = 'cut';

    public function calculatePointPositionByVertical(Segment $segment, Point $point): string
    {
        $k = ($segment->leftSide->y - $segment->rightSide->y) / ($segment->leftSide->x - $segment->rightSide->x);
        $b = $segment->rightSide->y - $k * $segment->rightSide->x;

        $yPosition = $this->calculateYPositionOfSegmentInPoint($k, $b, $point->x);

        if ($yPosition < $point->y) {
            return self::UP_POSITION;
        }

        if ($yPosition > $point->y) {
            return self::DOWN_POSITION;
        }

        return self::CUT_POSITION;
    }

    private function calculateYPositionOfSegmentInPoint(float $k, float $b, float $x)
    {
        return $k * $x + $b;
    }
}