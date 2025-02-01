<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $category1 = new Category("Football");
        $category2 = new Category("Boxing");
        $manager->persist($category1);
        $manager->persist($category2);
        $manager->flush();
        $this->addReference("category1", $category1);
        $this->addReference("category2", $category2);
    }
}