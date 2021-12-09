<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\Season;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public const SEASONS = [
        [
            "number" => 1,
            "year" => 2007,
            "description" => "Season 1",
            "program" => 'The Big Bang Theory'
        ],
        [
            "number" => 2,
            "year" => 2008,
            "description" => "Season 2",
            "program" => 'The Big Bang Theory'
        ],
        [
            "number" => 3,
            "year" => 2009,
            "description" => "Season 3",
            "program" => 'The Big Bang Theory'
        ],
        [
            "number" => 4,
            "year" => 2010,
            "description" => "Season 4",
            "program" => 'The Big Bang Theory'
        ],
        [
            "number" => 5,
            "year" => 2011,
            "description" => "Season 5",
            "program" => 'The Big Bang Theory'
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SEASONS as $seasonData) {

            $season = new Season;

            $season->setNumber($seasonData['number']);
            $season->setYear($seasonData['year']);
            $season->setDescription($seasonData['description']);
            $season->setProgram($this->getReference('program_' . $seasonData['program']));
            $this->addReference('season_' . $seasonData['number'], $season);
            $manager->persist($season);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
