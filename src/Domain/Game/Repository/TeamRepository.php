<?php
declare(strict_types=1);

namespace App\Domain\Game\Repository;

use App\Domain\Game\Sport;
use App\Domain\Game\Team;
use App\Domain\Game\Exception\NotFoundException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ObjectRepository;

class TeamRepository extends AbstractRepository
{
    private ObjectRepository $repository;

    public function __construct(DocumentManager $documentManager)
    {
        parent::__construct($documentManager);
        $this->repository = $this->documentManager->getRepository(Team::class);
    }

    /**
     * @param Sport $sport
     * @param string $name
     * @return Team
     * @throws NotFoundException
     */
    public function findBySportAndName(Sport $sport, string $name): Team
    {
        $model = $this->repository
            ->createQueryBuilder()
            ->field('nameVariations')->equals($name)
            ->field('sport')->references($sport)
            ->getQuery()
            ->getSingleResult();

        if ($model === null) {
            throw $this->teamNotFound($name);
        }

        return $model;
    }

    private function teamNotFound(string $name): NotFoundException
    {
        return new NotFoundException(sprintf('Team %s not found', $name));
    }
}