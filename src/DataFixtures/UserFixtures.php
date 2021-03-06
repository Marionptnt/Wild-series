<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;


    public function __construct(UserPasswordHasherInterface $passwordHasher)

    {

        $this->passwordHasher = $passwordHasher;
    }



    public function load(ObjectManager $manager): void

    {
        // Création d’un utilisateur de type “contributeur” (= auteur)

        $contributor = new User();
        $contributor->setEmail('contributor@monsite.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $hashedPassword = $this->passwordHasher->hashPassword($contributor,'contributorpassword');
        $contributor->setPassword($hashedPassword);

        $manager->persist($contributor);


        // Création d’un utilisateur de type “administrateur”

        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($admin,'adminpassword');
        $admin->setPassword($hashedPassword);
        $this->addReference('user_admin@monsite.com', $admin);
        $manager->persist($admin);


        $contributor = new User();
        $contributor->setEmail('marionp@monsite.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $hashedPassword = $this->passwordHasher->hashPassword($contributor,'marionp');
        $contributor->setPassword($hashedPassword);
        $this->addReference('user_marionp@monsite.com', $contributor);

        $manager->persist($contributor);

        // Sauvegarde des nouveaux utilisateurs :
        $manager->flush();
    }
}
