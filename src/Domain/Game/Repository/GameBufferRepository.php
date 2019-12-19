<?php
declare(strict_types=1);

namespace App\Domain\Game\Repository;

use App\Domain\Game\GameBuffer;
use App\Domain\Game\Exception\NotFoundException;

class GameBufferRepository extends AbstractRepository
{
    public function add(GameBuffer $game): void
    {
        $this->documentManager->persist($game);
    }

    /**
     * @param string $id
     * @return GameBuffer
     * @throws NotFoundException
     */
    public function find(string $id): GameBuffer
    {
        $game = $this->documentManager->find(GameBuffer::class, $id);

        if ($game === null) {
            throw $this->gameBufferNotFound();
        }
        return $game;
    }

    private function gameBufferNotFound(): NotFoundException
    {
        return new NotFoundException('Game buffer not found');
    }
}
