<?php
declare(strict_types=1);

namespace App\Domain\Game\Repository;

use App\Domain\Game\Language;
use App\Domain\Game\Exception\NotFoundException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ObjectRepository;

class LanguageRepository extends AbstractRepository
{
    private ObjectRepository $repository;

    public function __construct(DocumentManager $documentManager)
    {
        parent::__construct($documentManager);
        $this->repository = $this->documentManager->getRepository(Language::class);
    }

    /**
     * @param string $name
     * @return Language
     * @throws NotFoundException
     */
    public function findByName(string $name): Language
    {
        $model = $this->repository
            ->createQueryBuilder()
            ->field('nameVariations')->equals($name)
            ->getQuery()
            ->getSingleResult();

        if ($model === null) {
            throw $this->languageNotFound($name);
        }

        return $model;
    }

    private function languageNotFound(string $name): NotFoundException
    {
        return new NotFoundException(sprintf('Language %s not found', $name));
    }
}
