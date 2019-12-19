<?php
declare(strict_types=1);

namespace App\Domain\Game;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="games")
 */
class Game
{
    /**
     * @ODM\Id()
     */
    protected ?string $id = null;
    /**
     * @ODM\ReferenceOne(targetDocument=League::class, storeAs="id")
     */
    protected League $league;
    /**
     * @ODM\ReferenceOne(targetDocument=Team::class, storeAs="id")
     */
    protected Team $firstTeam;
    /**
     * @ODM\ReferenceOne(targetDocument=Team::class, storeAs="id")
     */
    protected Team $secondTeam;
    /**
     * @ODM\ReferenceOne(targetDocument=Language::class, storeAs="id")
     */
    protected Language $language;
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

    public function __construct()
    {
        $this->gameBuffers = new ArrayCollection();
    }

    public static function createFromGameBuffer(GameBuffer $gameBuffer): Game
    {
        $instance = new self();
        $instance->league = $gameBuffer->league();
        $instance->firstTeam = $gameBuffer->firstTeam();
        $instance->secondTeam = $gameBuffer->secondTeam();
        $instance->language = $gameBuffer->language();
        $instance->startTime = $gameBuffer->startTime();
        $instance->source = $gameBuffer->source();
        $instance->gameBuffers->add($gameBuffer);

        return $instance;
    }

    public function mergeGameBuffer(GameBuffer $gameBuffer): void
    {
        $this->gameBuffers->add($gameBuffer);
        $this->startTime = $gameBuffer->startTime();
        $this->source = $gameBuffer->source();
    }
}
