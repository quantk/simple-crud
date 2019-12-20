<?php
declare(strict_types=1);


namespace App\Infrastructure\Database;


use App\Infrastructure\Database\Contract\UidGenerator;
use Ramsey\Uuid\Uuid;

final class UuidGenerator implements UidGenerator
{
    public function generate(): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return (string)Uuid::uuid4();
    }
}