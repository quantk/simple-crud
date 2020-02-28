<?php
declare(strict_types=1);

namespace App\Segment;

use App\Segment\Domain\Segment;
use App\Segment\Domain\Segments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

final class SegmentRepository extends ServiceEntityRepository implements Segments
{
    /**
     * SectionRepository constructor.
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Segment::class);
    }

    /**
     * @param Segment $segment
     * @throws ORMException
     */
    public function add(Segment $segment): void
    {
        $this->getEntityManager()->persist($segment);
    }

    /**
     * @param string $uid
     * @throws ORMException
     */
    public function remove(string $uid): void
    {
        $segment = $this->findSegment($uid);
        $this->getEntityManager()->remove($segment);
    }

    /**
     * @return array|Segment[]
     */
    public function all(): array
    {
        return $this->findAll();
    }

    /**
     * @param string $uid
     * @return Segment|null
     */
    public function findSegment(string $uid): ?Segment
    {
        /** @var Segment|null $segment */
        $segment = $this->find($uid);
        return $segment;
    }
}