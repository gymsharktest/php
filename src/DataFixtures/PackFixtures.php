<?php

namespace App\DataFixtures;

use App\Entity\Pack;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PackFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $item1 = (new Pack())->setCount(250);
        $item2 = (new Pack())->setCount(500);
        $item3 = (new Pack())->setCount(1000);
        $item4 = (new Pack())->setCount(2000);
        $item5 = (new Pack())->setCount(5000);
        $manager->persist($item1);
        $manager->persist($item2);
        $manager->persist($item3);
        $manager->persist($item4);
        $manager->persist($item5);

        $manager->flush();
    }
}
