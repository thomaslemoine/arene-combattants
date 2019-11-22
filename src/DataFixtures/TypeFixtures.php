<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class TypeFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tableau= [
            'Elfe', 'Troll', 'Nain'
        ];

        foreach ($tableau as $val){
            $type = new Type();
            $type->setName($val);
            $manager->persist($type);
            $manager->persist($type);
            $this->addReference('type'.$val.'', $type);
            $manager->flush();
        }

    }
    public function getOrder()
    {
        return 1;
    }
}
