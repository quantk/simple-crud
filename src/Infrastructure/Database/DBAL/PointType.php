<?php
declare(strict_types=1);

namespace App\Infrastructure\Database\DBAL;


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

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::POINT;
    }
}