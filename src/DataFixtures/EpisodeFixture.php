<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixture extends Fixture implements DependentFixtureInterface
{

    public const EPISODES = [
        [
            "number" => 1,
            "title" => "Unaired Pilot",
            "synopsis" => "The first Pilot of what will become The Big Bang Theory. 
        Leonard and Sheldon are two awkward scientists who share an apartment. 
        They meet a drunk girl called Katie and invite her to stay at their place, 
        because she has nowhere to stay. The two guys have a female friend, also a scientist, called Gilda.",
            "season" => '1'
        ],
        [
            "number" => 2,
            "title" => "Pilot",
            "synopsis" => "A pair of socially awkward theoretical physicists meet their new neighbor Penny, 
        who is their polar opposite.",
            "season" => '1'
        ],
        [
            "number" => 3,
            "title" => "The Big Bran Hypothesis",
            "synopsis" => "Penny is furious with Leonard and Sheldon when they sneak into her apartment and 
        clean it while she is sleeping.",
            "season" => '1'
        ],
        [
            "number" => 4,
            "title" => "Unaired Pilot",
            "synopsis" => "Leonard gets upset when he discovers that Penny is seeing a new guy, so he tries 
        to trick her into going on a date with him.",
            "season" => '1'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::EPISODES as $episodeData) {

            $episode = new Episode();

            $episode->setTitle($episodeData['title']);

            $episode->setNumber($episodeData['number']);

            $episode->setSynopsis($episodeData['synopsis']);

            $episode->setSeason($this->getReference('season_' . $episodeData['season']));

            $manager->persist($episode);

            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
