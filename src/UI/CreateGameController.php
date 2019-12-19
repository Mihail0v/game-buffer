<?php
declare(strict_types=1);

namespace App\UI;

use App\Application\Command\CreateBufferGame\CreateBufferGameCommand;
use App\Domain\Game\Exception\NotFoundException;
use App\Domain\Game\Repository\LanguageRepository;
use App\Domain\Game\Repository\LeagueRepository;
use App\Domain\Game\Repository\SportRepository;
use App\Domain\Game\Repository\TeamRepository;
use App\Infrastructure\Game\DTO\GameDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class CreateGameController extends AbstractController
{
    private LanguageRepository $languageRepository;
    private SportRepository $sportRepository;
    private TeamRepository $teamRepository;
    private LeagueRepository $leagueRepository;
    private MessageBusInterface $bus;

    public function __construct(
        LanguageRepository $languageRepository,
        SportRepository $sportRepository,
        TeamRepository $teamRepository,
        LeagueRepository $leagueRepository,
        MessageBusInterface $bus
    ) {
        $this->languageRepository = $languageRepository;
        $this->sportRepository = $sportRepository;
        $this->teamRepository = $teamRepository;
        $this->leagueRepository = $leagueRepository;
        $this->bus = $bus;
    }

    /**
     * @Route("create")
     * @param Request $request
     * @param SerializerInterface $normalizer
     * @return JsonResponse
     */
    public function __invoke(Request $request, SerializerInterface $normalizer)
    {
        /** @var GameDTO[] $data */
        $data = $normalizer->deserialize($request->getContent(), GameDTO::class . '[]', 'json');
        $response = [];

        foreach ($data as $i => $gameDTO) {
            try {
                $language = $this->languageRepository->findByName($gameDTO->language);
                $sport = $this->sportRepository->findByName($gameDTO->sport);
                $league = $this->leagueRepository->findBySportAndName($sport, $gameDTO->league);
                $team1 = $this->teamRepository->findBySportAndName($sport, $gameDTO->firstTeam);
                $team2 = $this->teamRepository->findBySportAndName($sport, $gameDTO->secondTeam);
                $startTime = $gameDTO->startTime;
                $source = $gameDTO->source;

                $this->bus->dispatch(
                    new CreateBufferGameCommand(
                        $language->id(),
                        $league->id(),
                        $team1->id(),
                        $team2->id(),
                        $startTime,
                        $source
                    )
                );
            } catch (NotFoundException $ex) {
                $response[] = sprintf('Request #%d failed: %s', $i, $ex->getMessage());
            }
        }
        return new JsonResponse($response, count($response) > 0 ? 500 : 200);
    }
}
