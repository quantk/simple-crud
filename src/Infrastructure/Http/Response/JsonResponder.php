<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Response;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class JsonResponder implements Responder
{

    public function collection(array $data, int $status = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse(['data' => $data], $status, $headers);
    }

    /**
     * @param mixed $data
     * @param int $status
     * @param array $headers
     * @return Response
     */
    public function item($data, int $status = 200, array $headers = []): Response
    {
        return new JsonResponse(['data' => $data], $status, $headers);
    }

    /**
     * @param int $status
     * @param array $headers
     * @return Response
     */
    public function emptyResponse(int $status = 200, array $headers = []): Response
    {
        return new JsonResponse([], $status, $headers);
    }

    /**
     * @param array $messages
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    public function error(array $messages, int $status = 400, array $headers = [])
    {
        return new JsonResponse([
            'errors' => $messages
        ], $status, $headers);
    }
}