<?php
declare(strict_types=1);

namespace App\Domain\Game;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Mapping\Annotations\QueryResultDocument;

/**
 * @QueryResultDocument()
 */
class NormalizedGame
{
    /**
     * @ODM\Id()
     */
    protected string $id;
    /**
     * @ODM\Field
     */
    protected string $league;
    /**
     * @ODM\Field
     */
    protected string $firstTeam;
    /**
     * @ODM\Field
     */
    protected string $secondTeam;
    /**
     * @ODM\Field
     */
    protected string $language;
    /**
     * @ODM\ReferenceMany(targetDocument=GameBuffer::class, storeAs="id")
     */
    protected Collection $gameBuffers;
    /**
     * @ODM\Field(type="date_immutable")
     */
    protected DateTimeImmutable $startTime;
    /**
     * @ODM\Field
     */
    protected string $source;

    public function getId(): string
    {
        return $this->id;
    }

    public function getLeague(): string
    {
        return $this->league;
    }

    public function getFirstTeam(): string
    {
        return $this->firstTeam;
    }

    public function getSecondTeam(): string
    {
        return $this->secondTeam;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getGameBuffersCount(): int
    {
        return $this->gameBuffers->count();
    }

    public function getStartTime(): DateTimeImmutable
    {
        return $this->startTime;
    }

    public function getSource(): string
    {
        return $this->source;
    }
}
