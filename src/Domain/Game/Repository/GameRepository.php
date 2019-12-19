<?php
declare(strict_types=1);

namespace App\Domain\Game\Repository;

use App\Domain\Game\Game;
use App\Domain\Game\GameBuffer;
use App\Domain\Game\NormalizedGame;
use App\Domain\Game\Exception\NotFoundException;
use DateTime;
use DateTimeImmutable as DateTimeImmutableAlias;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectRepository;
use function sprintf;

class GameRepository extends AbstractRepository
{
    private ObjectRepository $repository;

    public function __construct(DocumentManager $documentManager)
    {
        parent::__construct($documentManager);
        $this->repository = $this->documentManager->getRepository(Game::class);
    }

    /**
     * @param GameBuffer $gameBuffer
     * @return Game
     * @throws NotFoundException
     */
    public function findByBufferGame(GameBuffer $gameBuffer): Game
    {
        $qb = $this->documentManager->createQueryBuilder(Game::class);

        $qb
            ->field('language')->references($gameBuffer->language())
            ->field('league')->references($gameBuffer->league())
            ->field('startTime')->range(
                DateTime::createFromImmutable($gameBuffer->startTime())->modify('-26 hours'),
                DateTime::createFromImmutable($gameBuffer->startTime()->modify('+26 hours'))
            )
            ->addAnd(
                $qb->expr()->addOr(
                    $qb->expr()->field('firstTeam')->references($gameBuffer->firstTeam()),
                    $qb->expr()->field('firstTeam')->references($gameBuffer->secondTeam())
                ),
                $qb->expr()->addOr(
                    $qb->expr()->field('secondTeam')->references($gameBuffer->firstTeam()),
                    $qb->expr()->field('secondTeam')->references($gameBuffer->secondTeam())
                )
            );

        $result = $qb->getQuery()->getSingleResult();

        if ($result === null) {
            throw $this->gameNotFound();
        }
        return $result;
    }

    /**
     * @param DateTimeImmutableAlias|null $dateTime
     * @param string|null $source
     * @return NormalizedGame
     * @throws NotFoundException
     */
    public function getRandomGame(?DateTimeImmutableAlias $dateTime = null, ?string $source = null): NormalizedGame
    {
        $ab = $this->documentManager->createAggregationBuilder(Game::class);
        $classMetadata = $this->documentManager->getClassMetadata(Game::class);

        $ab->hydrate(NormalizedGame::class);

        $match = $ab->match();

        if ($source !== null) {
            $match->field('source')->equals($source);
        }
        if ($dateTime !== null) {
            $match->field('startTime')->range(
                DateTime::createFromImmutable($dateTime)->modify('- 26 hours'),
                DateTime::createFromImmutable($dateTime)->modify('+ 26 hours')
            );
        }

        $ab->sample(1);

        foreach ($classMetadata->fieldMappings as $field => $mapping) {
            if (($mapping['association'] ?? null) !== ClassMetadata::REFERENCE_ONE) {
                continue;
            }
            $ab->lookup($mapping['targetDocument'])
                ->localField($mapping['fieldName'])
                ->foreignField('_id')
                ->alias($mapping['fieldName']);
            $ab->unwind(sprintf('$%s', $mapping['fieldName']));
            $ab->addFields()
                ->field($mapping['fieldName'])->expression(sprintf('$%s.name', $mapping['fieldName']));
        }

        $result = $ab->execute()->current();

        if (!$result instanceof NormalizedGame) {
            throw $this->gameNotFound();
        }

        return $result;
    }

    public function add(Game $game): void
    {
        $this->documentManager->persist($game);
    }

    private function gameNotFound(): NotFoundException
    {
        return new NotFoundException('Game not found');
    }
}
