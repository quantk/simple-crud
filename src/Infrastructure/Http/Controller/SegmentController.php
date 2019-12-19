<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;


use App\Domain\Section\SegmentRepository;
use App\Infrastructure\Http\Response\Responder;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class SegmentController
 * @package App\Infrastructure\Http\Controller
 * @Route("/segments")
 */
final class SegmentController
{
    /**
     * @param SegmentRepository $segmentRepository
     * @param Responder $responder
     * @return Response
     * @throws DBALException
     * @Route("/list", name="segment_list", methods={"GET"})
     */
    public function list(
        SegmentRepository $segmentRepository,
        Responder $responder
    )
    {
        $segments = $segmentRepository->all();
        return $responder->ok($segments);
    }
}