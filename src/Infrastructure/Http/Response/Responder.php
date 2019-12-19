<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Response;


use Symfony\Component\HttpFoundation\Response;

interface Responder
{
    public function ok(array $data, int $status = 200, array $headers = []): Response;
}