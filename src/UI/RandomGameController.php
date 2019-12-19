<?php
declare(strict_types=1);

namespace App\UI;

use App\Domain\Game\Exception\NotFoundException;
use App\Domain\Game\Repository\GameRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class RandomGameController extends AbstractController
{
    /**
     * @Route("/random")
     * @param GameRepository $gameRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return Response
     * @throws NotFoundException
     */
    public function __invoke(GameRepository $gameRepository, SerializerInterface $serializer, Request $request): Response
    {
        $startTime = $request->query->get('startTime');
        $source = $request->query->get('source');
        return new Response(
            $serializer->serialize(
                $gameRepository->getRandomGame(
                    $startTime === null ? null : DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $startTime),
                    $source
                ),
                'json'
            )
        );
    }
}
