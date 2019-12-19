<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Response;


use Symfony\Component\HttpFoundation\JsonResponse;

final class JsonResponder implements Responder
{

    public function ok(array $data, int $status = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse(['data' => $data], $status, $headers);
    }
}