<?php
declare(strict_types=1);

namespace App\Application\Command\CreateBufferGame;

use DateTimeImmutable;

final class CreateBufferGameCommand
{
    public string $languageId;
    public string $leagueId;
    public string $firstTeamId;
    public string $secondTeamId;
    public DateTimeImmutable $startTime;
    public string $source;

    public function __construct(
        string $languageId,
        string $leagueId,
        string $firstTeamId,
        string $secondTeamId,
        DateTimeImmutable $startTime,
        string $source
    ) {
        $this->languageId = $languageId;
        $this->leagueId = $leagueId;
        $this->firstTeamId = $firstTeamId;
        $this->secondTeamId = $secondTeamId;
        $this->startTime = $startTime;
        $this->source = $source;
    }
}
