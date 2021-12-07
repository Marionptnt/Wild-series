<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Actor;

class ActorFixtures extends Fixture
{

    public const ACTORS = [
        'Jim Parsons', 'Kaley Cuoco', 'Johnny Galecki'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ACTORS as $key => $actorName) {

            $actor = new Actor();

            $actor->setName($actorName);

            $manager->persist($actor);

            $this->addReference('actor_' . $key, $actor);
        }

        $manager->flush();
    }
}
