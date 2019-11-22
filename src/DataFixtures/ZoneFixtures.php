<?php

namespace App\DataFixtures;

use App\Entity\Zone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ZoneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tableau= [
            'Prairie', 'Désert', 'Forêt'
        ];

        foreach ($tableau as $val){
            $zone = new Zone();
            $zone->setName($val);
            $manager->persist($zone);
            $manager->flush();
        }

    }
    public function getOrder()
    {
        return 3;
    }
}
