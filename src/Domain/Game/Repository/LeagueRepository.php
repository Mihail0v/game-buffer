<?php
declare(strict_types=1);

namespace App\Domain\Game\Repository;

use App\Domain\Game\League;
use App\Domain\Game\Sport;
use App\Domain\Game\Exception\NotFoundException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ObjectRepository;

class LeagueRepository extends AbstractRepository
{
    private ObjectRepository $repository;

    public function __construct(DocumentManager $documentManager)
    {
        parent::__construct($documentManager);
        $this->repository = $this->documentManager->getRepository(League::class);
    }

    /**
     * @param Sport $sport
     * @param string $name
     * @return League
     * @throws NotFoundException
     */
    public function findBySportAndName(Sport $sport, string $name): League
    {
        $model = $this->repository
            ->createQueryBuilder()
            ->field('nameVariations')->equals($name)
            ->field('sport')->references($sport)
            ->getQuery()
            ->getSingleResult();

        if ($model === null) {
            throw $this->leagueNotFound($name);
        }

        return $model;
    }

    private function leagueNotFound(string $name): NotFoundException
    {
        return new NotFoundException(sprintf('League %s not found', $name));
    }
}
