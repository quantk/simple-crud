<?php
declare(strict_types=1);


namespace App\Infrastructure\Database;


use Ramsey\Uuid\Uuid;

interface UidGenerator
{
    public function generate(): string;
}