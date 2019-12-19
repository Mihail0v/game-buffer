<?php
declare(strict_types=1);

namespace App\Infrastructure\Game\DTO;

use DateTimeImmutable;

class GameDTO
{
    public string $league;
    public string $firstTeam;
    public string $secondTeam;
    public string $sport;
    public string $language;
    public DateTimeImmutable $startTime;
    public string $source;

    public function setStartTime(string $time): void
    {
        $this->startTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $time);
    }
}
