<?php
declare(strict_types=1);

namespace App\Segment\Domain;


interface NewSegment
{
    public function assemble(): Segment;
}