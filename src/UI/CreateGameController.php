<?php
declare(strict_types=1);

namespace App\UI;

use App\Application\Command\CreateBufferGame\CreateBufferGameCommand;
use App\Domain\Game\Exception\NotFoundException;
use App\Domain\Game\Repository\LanguageRepository;
use App\Domain\Game\Repository\LeagueRepository;
use App\Domain\Game\Repository\SportRepository;
use App\Domain\Game\Repository\TeamRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreateGameController extends AbstractController
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
     * @throws NotFoundException
     */
    public function __invoke()
    {
        $requests = [
            [
                'language' => 'русский',
                'sport' => 'хоккей',
                'league' => 'лига уефа',
                'firstTeam' => 'реал',
                'secondTeam' => 'барса',
            ],
            [
                'language' => 'русский',
                'sport' => 'хоккей',
                'league' => 'лига уефа',
                'firstTeam' => 'реал',
                'secondTeam' => 'барса',
            ],
            [
                'language' => 'русский',
                'sport' => 'хоккей',
                'league' => 'лига уефа',
                'firstTeam' => 'реал',
                'secondTeam' => 'барса',
            ],
        ];
        $response = [];
        foreach ($requests as $i => $request) {
            try {
                $language = $this->languageRepository->findByName($request['language']);
                $sport = $this->sportRepository->findByName($request['sport']);
                $league = $this->leagueRepository->findBySportAndName($sport, $request['league']);
                $team1 = $this->teamRepository->findBySportAndName($sport, $request['firstTeam']);
                $team2 = $this->teamRepository->findBySportAndName($sport, $request['secondTeam']);
                $startTime = new DateTimeImmutable();
                $source = 'blabla.ru' . time();

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
