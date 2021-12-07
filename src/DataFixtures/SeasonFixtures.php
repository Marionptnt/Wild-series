<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Season;

class SeasonFixtures extends Fixture
{
    public const SEASONS = [
        '1', '2', '3', '4', '5'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SEASONS as $key => $seasonNumber) {

            $season = new Season();

            $season->setNumber($seasonNumber);


            $manager->persist($season);

            $this->addReference('season_' . $key, $season);
        }

        $manager->flush();
    }
}
