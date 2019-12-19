<?php
declare(strict_types=1);

namespace App\Application\Command\CreateBufferGame;

use App\Application\Command\MergeBuffer\MergeBufferCommand;
use App\Domain\Game\GameBuffer;
use App\Domain\Game\Language;
use App\Domain\Game\League;
use App\Domain\Game\Sport;
use App\Domain\Game\Team;
use App\Domain\Game\Repository\GameBufferRepository;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class CreateBufferGameHandler implements MessageHandlerInterface
{
    private GameBufferRepository $bufferRepository;
    private MessageBusInterface $messageBus;

    /**
     * @param CreateBufferGameCommand $command
     * @throws MongoDBException
     */
    public function __invoke(CreateBufferGameCommand $command)
    {
        $language = $this->bufferRepository->getReference(Language::class, $command->languageId);
        $league = $this->bufferRepository->getReference(League::class, $command->leagueId);
        $firstTeam = $this->bufferRepository->getReference(Team::class, $command->firstTeamId);
        $secondTeam = $this->bufferRepository->getReference(Team::class, $command->secondTeamId);

        $gameBuffer = GameBuffer::create(
            $league,
            $firstTeam,
            $secondTeam,
            $language,
            $command->startTime,
            $command->source
        );

        $this->bufferRepository->add($gameBuffer);
        $this->bufferRepository->save();

        $this->messageBus->dispatch(new MergeBufferCommand($gameBuffer->id()));
    }

    public function __construct(GameBufferRepository $bufferRepository, MessageBusInterface $messageBus)
    {
        $this->bufferRepository = $bufferRepository;
        $this->messageBus = $messageBus;
    }
}
