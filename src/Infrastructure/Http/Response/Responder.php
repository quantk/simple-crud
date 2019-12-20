<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Response;


use Symfony\Component\HttpFoundation\Response;

interface Responder
{
    public function collection(array $data, int $status = 200, array $headers = []): Response;

    public function item($data, int $status = 200, array $headers = []): Response;

    public function emptyResponse(int $status = 200, array $headers = []): Response;

    public function error(array $messages, int $status = 400, array $headers = []);
}