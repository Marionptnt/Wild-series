<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Program;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $program = new Program();

        $program->setTitle('The Big Bang Theorie');

        $program->setPoster('https://upload.wikimedia.org/wikipedia/fr/6/69/BigBangTheory_Logo.png');

        $program->setSummary('Une bande de docteurs en science partagent un quotidien fait de jeux vidéo, d\'équations et d\'amitié.');

        $program->setCategory($this->getReference('category_5'));


        //ici les acteurs sont insérés via une boucle pour être DRY mais ce n'est pas obligatoire

        for ($i = 0; $i < count(ActorFixtures::ACTORS); $i++) {

            $program->addActor($this->getReference('actor_' . $i));
        }



        $manager->persist($program);

        $manager->flush();
    }

    public function getDependencies()

    {

        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend

        return [

            ActorFixtures::class,

            CategoryFixtures::class,


        ];
    }
}
