<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{

    const CATEGORIES = [
        'Humour',
        'Aventure',
        'Animation',
        'Fantastique',
        'Horreur',
        'Action'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $categoryData) {
            $category = new Category();
            $category->setName($categoryData);
            $manager->persist($category);
            $this->addReference('category_' . $categoryData, $category);
        }
        $manager->flush();
    }
}
