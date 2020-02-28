<?php
declare(strict_types=1);

namespace App\Segment\Domain;


interface Segments
{
    /**
     * @param Segment $segment
     */
    public function add(Segment $segment): void;

    /**
     * @param string $uid
     * @return Segment|null
     */
    public function findSegment(string $uid): ?Segment;

    /**
     * @param string $uid
     */
    public function remove(string $uid): void;

    /**
     * @return array|Segment[]
     */
    public function all(): array;
}