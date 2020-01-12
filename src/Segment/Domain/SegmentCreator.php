<?php
declare(strict_types=1);

namespace App\Segment\Domain;


interface SegmentCreator
{
    public function create(NewSegment $newSegment): void;
}