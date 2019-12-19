<?php
declare(strict_types=1);

namespace App\UI;

use App\Domain\Game\Exception\NotFoundException;
use App\Domain\Game\Repository\GameRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class RandomGameController extends AbstractController
{
    /**
     * @Route("/random")
     * @param GameRepository $gameRepository
     * @param SerializerInterface $serializer
     * @return Response
     * @throws NotFoundException
     */
    public function __invoke(GameRepository $gameRepository, SerializerInterface $serializer): Response
    {
        return new Response(
            $serializer->serialize(
                $gameRepository->getRandomGame(new DateTimeImmutable()),
                'json'
            )
        );
    }
}
