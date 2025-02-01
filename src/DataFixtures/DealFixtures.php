<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Deal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class DealFixtures extends Fixture
{

    /**
     * @inheritDoc
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $category1 = $this->getReference('category1', Category::class);
        for ($i = 0; $i < 10; $i++) {
            $deal = new Deal();
            $deal->setName('Deal '.$i)
                ->setDescription('Description du deal '.$i)
                ->setPrice(rand(1, 100))
                ->setEnable((bool) random_int(0, 1));
            $manager->persist($deal);
            $deal->addCategory($category1);
        }
        $category2 = $this->getReference('category2', Category::class);
        for ($i = 11; $i < 20; $i++) {
            $deal = new Deal();
            $deal->setName('Deal '.$i)
                ->setDescription('Description du deal '.$i)
                ->setPrice(rand(1, 100))
                ->setEnable((bool) random_int(0, 1));
            $manager->persist($deal);
            $deal->addCategory($category2);
        }
        $manager->flush();
    }
}