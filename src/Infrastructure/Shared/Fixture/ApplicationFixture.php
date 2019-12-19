<?php

namespace App\Infrastructure\Shared\Fixture;

use App\Document\Language;
use App\Document\League;
use App\Document\Sport;
use App\Document\Team;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ApplicationFixture extends Fixture
{
    private const LANGUAGES = [
        'Английский' => ['eng', 'english', 'англ', 'английский'],
        'Русский' => ['русс', 'русский', 'rus', 'russian'],
        'Украинский' => ['украинский', 'укр', 'ukrainian', 'ukr']
    ];
    private const TEAMS = [
        'Реал мадрид' => ['real m', 'real', 'real madrid', 'реал', 'реал мадрид'],
        'Барселона' => ['barcelona', 'барселона', 'барса'],
    ];
    private CONST SPORTS = [
        'Футбол' => ['football', 'футбол', 'futbol'],
        'Хоккей' => ['hockey', 'hokei', 'хоккей'],
    ];
    private const LEAGUES = [
        'Лига чемпионов УЕФА' => ['Liga UEFA', 'League UEFA', 'лига уефа'],
        'Бундеслига' => ['бундеслига', 'bundesliga'],
    ];

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        foreach (self::LANGUAGES as $name => $nameVariations) {
            $language = Language::create($name, $nameVariations);
            $manager->persist($language);
        }
        $manager->flush();
        $persistedTeams = [];
        foreach (self::SPORTS as $name => $nameVariations) {
            $sport = Sport::create($name, $nameVariations);
            $manager->persist($sport);
            foreach (self::TEAMS as $teamName => $teamNameVariations) {
                $team = Team::create($sport, $teamName, $teamNameVariations);
                $manager->persist($team);
                $persistedTeams[] = $team;
            }
            foreach (self::LEAGUES as $leagueName => $leagueNameVariations) {
                $league = League::create($sport, $leagueName, $leagueNameVariations);
                $manager->persist($league);
            }
        }
        $manager->flush();
    }
}
