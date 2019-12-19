<?php
declare(strict_types=1);

namespace App\Application\Command\MergeBuffer;

use App\Domain\Game\Game;
use App\Domain\Game\Exception\NotFoundException;
use App\Domain\Game\Repository\GameBufferRepository;
use App\Domain\Game\Repository\GameRepository;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class MergeBufferHandler implements MessageHandlerInterface
{
    private GameBufferRepository $gameBufferRepository;
    private GameRepository $gameRepository;

    /**
     * @param MergeBufferCommand $command
     * @throws NotFoundException
     * @throws MongoDBException
     */
    public function __invoke(MergeBufferCommand $command)
    {
        $gameBuffer = $this->gameBufferRepository->find($command->bufferId);

        try {
            $game = $this->gameRepository->findByBufferGame($gameBuffer);
            $game->mergeGameBuffer($gameBuffer);
        } catch (NotFoundException $e) {
            $game = Game::createFromGameBuffer($gameBuffer);
        }
        $this->gameRepository->add($game);
        $this->gameRepository->save();
    }

    public function __construct(GameBufferRepository $gameBufferRepository, GameRepository $gameRepository)
    {
        $this->gameBufferRepository = $gameBufferRepository;
        $this->gameRepository = $gameRepository;
    }
}
