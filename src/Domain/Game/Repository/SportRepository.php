<?php
declare(strict_types=1);

namespace App\Domain\Game\Repository;

use App\Domain\Game\Sport;
use App\Domain\Game\Exception\NotFoundException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ObjectRepository;

class SportRepository extends AbstractRepository
{
    private ObjectRepository $repository;

    public function __construct(DocumentManager $documentManager)
    {
        parent::__construct($documentManager);
        $this->repository = $this->documentManager->getRepository(Sport::class);
    }

    /**
     * @param string $name
     * @return Sport
     * @throws NotFoundException
     */
    public function findByName(string $name): Sport
    {
        $model = $this->repository
            ->createQueryBuilder()
            ->field('nameVariations')->equals($name)
            ->getQuery()
            ->getSingleResult();

        if ($model === null) {
            throw $this->sportNotFound($name);
        }

        return $model;
    }

    private function sportNotFound(string $name): NotFoundException
    {
        return new NotFoundException(sprintf('Sport %s not found', $name));
    }
}