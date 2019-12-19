<?php
declare(strict_types=1);


namespace App\Domain\Section;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\FetchMode;

final class SegmentRepository
{
    /**
     * @var Connection
     */
    private Connection $connection;


    /**
     * SectionRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Segment $section
     * @throws DBALException
     */
    public function add(Segment $section): void
    {
        $this->connection->query(
            'INSERT INTO "simple-crud".public.segments(uid, x, y) VALUES (?, ?, ?)'
        )->execute([
            $section->uid,
            "({$section->leftSide->x},{$section->leftSide->y})",
            "({$section->rightSide->x},{$section->rightSide->y})",
        ]);
    }

    /**
     * @param Segment $section
     * @throws DBALException
     */
    public function remove(Segment $section): void
    {
        $this->connection->query(
            'DELETE FROM "simple-crud".public.segments WHERE uid = ?'
        )->execute([$section->uid]);
    }

    /**
     * @return array|Segment[]
     * @throws DBALException
     */
    public function all(): array
    {
        $segments = $this->connection->query(
            'SELECT * FROM "simple-crud".public.segments'
        )->fetchAll(FetchMode::ASSOCIATIVE);

        $result = [];
        foreach ($segments as $segment) {
            [$x1, $y1] = explode(',',trim($segment['left_side'], '()'));
            [$x2, $y2] = explode(',',trim($segment['right_side'],'()'));
            $result[] = Segment::create(
                (string)$segment['uid'],
                Point::create((float)$x1,(float)$y1),
                Point::create((float)$x2,(float)$y2),
            );
        }

        return $result;
    }
}