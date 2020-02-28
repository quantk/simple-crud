<?php
declare(strict_types=1);

namespace App\Infrastructure\Database\DBAL;


use App\Segment\Domain\Point;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class PointType extends Type
{
    public const POINT = 'point';

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return self::POINT;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Point
    {
        return Point::createFromRaw($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Point) {
            return $value->toString();
        } else {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::POINT;
    }
}