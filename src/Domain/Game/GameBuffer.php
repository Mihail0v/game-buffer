<?php
declare(strict_types=1);

namespace App\Domain\Game;

use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="game_buffer")
 */
class GameBuffer
{
    /**
     * @ODM\Id()
     */
    protected ?string $id = null;
    /**
     * @ODM\ReferenceOne(targetDocument=League::class)
     */
    protected ?League $league;
    /**
     * @ODM\ReferenceOne(targetDocument=Team::class)
     */
    protected ?Team $firstTeam;
    /**
     * @ODM\ReferenceOne(targetDocument=Team::class)
     */
    protected ?Team $secondTeam;
    /**
     * @ODM\ReferenceOne(targetDocument=Language::class)
     */
    protected ?Language $language;
    /**
     * @ODM\Field(type="date_immutable")
     */
    protected ?DateTimeImmutable $startTime;
    /**
     * @ODM\Field
     */
    protected ?string $source;

    public static function create(
        League $league,
        Team $firstTeam,
        Team $secondTeam,
        Language $language,
        DateTimeImmutable $startTime,
        string $source
    ): GameBuffer {
        $instance = new self();
        $instance->league = $league;
        $instance->firstTeam = $firstTeam;
        $instance->secondTeam = $secondTeam;
        $instance->language = $language;
        $instance->startTime = $startTime;
        $instance->source = $source;

        return $instance;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function language(): Language
    {
        return $this->language;
    }

    public function firstTeam(): Team
    {
        return $this->firstTeam;
    }

    public function secondTeam(): Team
    {
        return $this->secondTeam;
    }

    public function league(): League
    {
        return $this->league;
    }

    public function startTime(): DateTimeImmutable
    {
        return $this->startTime;
    }

    public function source(): string
    {
        return $this->source;
    }
}
