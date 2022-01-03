<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Actor;

class ActorFixtures extends Fixture
{
    public const ACTORS_THE_WALKING_DEAD = [
        'Andrew Lincoln',
        'Norman Reedus',
        'Lauren Cohan',
        'Danai Gurira',
        'Lesley-Ann Brandt'
    ];

    public const ACTORS_THE_BIG_BANG_THEORY = [
        'Jim Parsons',
        'Johnny Galecki',
        'Kaley Cuoco',
        'Simon Helberg',
        'Kunal Nayyar',
        'Mayim Bialik',
        'Melissa Rauch',
        'Sara Gilbert',
        'Kevin Sussman'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ACTORS_THE_WALKING_DEAD as $i => $actorData) {
            $actor=new Actor();
            $actor->setName($actorData);
            $this->addReference('actor_the_walking_dead_' . $i, $actor);
            $manager->persist($actor);
        }
        foreach (self::ACTORS_THE_BIG_BANG_THEORY as $i => $actorData) {
            $actor=new Actor();
            $actor->setName($actorData);
            $this->addReference('actor_the_big_bang_theory_' . $i, $actor);
            $manager->persist($actor);
        }
        $manager->flush();
    }
}