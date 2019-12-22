<?php
declare(strict_types=1);


namespace App\Domain\Segment;

use App\Domain\Segment\Contract\Segments;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\ParameterType;

final class SegmentRepository implements Segments
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
     * @param Segment $segment
     * @throws DBALException
     */
    public function add(Segment $segment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO segments(uid, left_side, right_side, created_at) VALUES (?, ?, ?, ?)'
        );
        $statement->bindValue(1, $segment->uid, ParameterType::STRING);
        $statement->bindValue(2, $segment->leftSide->toString(), ParameterType::STRING);
        $statement->bindValue(3, $segment->rightSide->toString(), ParameterType::STRING);
        $statement->bindValue(4, $segment->createdAt->format('Y-m-d H:i:s'), ParameterType::INTEGER);
        $statement->execute();
    }

    /**
     * @param string $uid
     * @return Segment|null
     * @throws DBALException
     */
    public function find(string $uid): ?Segment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM segments WHERE uid = ? LIMIT 1'
        );
        $statement->bindValue(1, $uid);

        $statement->execute();

        $rawSegment = $statement->fetch(FetchMode::ASSOCIATIVE);

        if ($rawSegment === false) {
            return null;
        }

        return $this->hydrate($rawSegment);
    }

    private function hydrate(array $raw): Segment
    {
        return Segment::create(
            (string)$raw['uid'],
            Point::createFromRaw($raw['left_side']),
            Point::createFromRaw($raw['right_side']),
            );
    }

    /**
     * @param string $uid
     * @throws DBALException
     */
    public function remove(string $uid): void
    {
        $statement = $this->connection->prepare(
            'DELETE FROM segments WHERE uid = ?'
        );
        $statement->bindValue(1, $uid);
        $statement->execute();
    }

    /**
     * @return array|Segment[]
     * @throws DBALException
     */
    public function all(): array
    {
        $segments = $this->connection->query(
            'SELECT * FROM segments'
        )->fetchAll(FetchMode::ASSOCIATIVE);

        $result = [];
        foreach ($segments as $segment) {
            $result[] = $this->hydrate($segment);
        }

        return $result;
    }
}