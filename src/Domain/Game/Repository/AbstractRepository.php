<?php
declare(strict_types=1);

namespace App\Domain\Game\Repository;

use Doctrine\ODM\MongoDB\DocumentManager;

abstract class AbstractRepository
{
    /**
     * @var DocumentManager
     */
    protected DocumentManager $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function getReference(string $document, string $id): object
    {
        return $this->documentManager->getReference($document, $id);
    }

    /**
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function save(): void
    {
        $this->documentManager->flush();
    }
}
