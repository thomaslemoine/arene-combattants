<?php

namespace App\DataFixtures;

use App\Entity\Type;
use App\Entity\Fighter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker;

class FighterFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $type = new Type();

        for ($i = 0; $i < 5; $i++) {
            $fighter = new Fighter();
            $fighter->setName($faker->name);
            $fighter->setStrength($faker->randomDigit);
            $fighter->setIntelligence($faker->randomDigit);
            $fighter->setPv($faker->randomDigit);

            $fighter->setType($this->getReference('typeElfe'));
            $manager->persist($fighter);
        }

        for ($i = 0; $i < 5; $i++) {
            $fighter = new Fighter();
            $fighter->setName($faker->name);
            $fighter->setStrength($faker->randomDigit);
            $fighter->setIntelligence($faker->randomDigit);
            $fighter->setPv($faker->randomDigit);

            $fighter->setType($this->getReference('typeTroll'));
            $manager->persist($fighter);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}
