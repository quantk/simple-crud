<?php
declare(strict_types=1);


namespace App\Segment\Domain;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class Segment
 * @package App\Domain\Segment
 * @ORM\Entity(repositoryClass="App\Segment\SegmentRepository")
 */
class Segment
{
    /**
     * @var string|null
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true, name="uid")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private ?string $uid;
    /**
     * @var Point
     * @ORM\Column(type="point", name="left_side")
     */
    private Point $leftSide;
    /**
     * @var Point
     * @ORM\Column(type="point", name="right_side")
     */
    private Point $rightSide;
    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="created_at")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->uid;
    }

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

        $verticalCoordinate = $point->calculateVerticalPosition($segment->leftSide, $segment->rightSide);

        return $point->getPositionByVerticalCoordinate($verticalCoordinate);
    }

    public function toArray()
    {
        return [
            'uid' => $this->uid,
            'left_side' => $this->leftSide->toArray(),
            'right_side' => $this->rightSide->toArray(),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s')
        ];
    }
}