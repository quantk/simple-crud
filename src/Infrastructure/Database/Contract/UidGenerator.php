<?php
declare(strict_types=1);


namespace App\Infrastructure\Database\Contract;


interface UidGenerator
{
    public function generate(): string;
}