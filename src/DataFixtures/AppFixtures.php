<?php

namespace App\DataFixtures;

use App\Entity\Fighter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $fighter = new Fighter();
            $fighter->setName($faker->name);
            $fighter->setStrength($faker->randomDigit);
            $fighter->setIntelligence($faker->randomDigit);
            $fighter->setPv($faker->randomDigit);
            $manager->persist($fighter);
        }

        $manager->flush();
    }
}
