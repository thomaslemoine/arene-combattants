<?php

namespace App\DataFixtures;

use App\Entity\Type;
use App\Entity\Fighter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker;
use Cocur\Slugify\Slugify;

class FighterFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $type = new Type();

        for ($i = 0; $i < 20; $i++) {
            $fighter = new Fighter();
            $fighter->setName($faker->name);
            $fighter->setStrength(10);
            $fighter->setIntelligence(10);
            $fighter->setPv(50);
            $fighter->setSlug((new Slugify())->slugify($fighter->getName()));

            $fighter->setType($this->getReference('typeNain'));
            $manager->persist($fighter);
        }

        for ($i = 0; $i < 20; $i++) {
            $fighter = new Fighter();
            $fighter->setName($faker->name);
            $fighter->setStrength(10);
            $fighter->setIntelligence(10);
            $fighter->setPv(50);
            $fighter->setSlug((new Slugify())->slugify($fighter->getName()));

            $fighter->setType($this->getReference('typeElfe'));
            $manager->persist($fighter);
        }


        for ($i = 0; $i < 20; $i++) {
            $fighter = new Fighter();
            $fighter->setName($faker->name);
            $fighter->setStrength(10);
            $fighter->setIntelligence(10);
            $fighter->setPv(50);
            $fighter->setSlug((new Slugify())->slugify($fighter->getName()));

            $fighter->setType($this->getReference('typeTroll'));
            $manager->persist($fighter);
        }



/*
for ($i = 0; $i < 9; $i++) {
    $fighter = new Fighter();
    $fighter->setName($faker->name);
    $fighter->setStrength($faker->randomDigit);
    $fighter->setIntelligence($faker->randomDigit);
    $fighter->setPv($faker->randomDigitNotNull);

    $fighter->setType($this->getReference('typeTroll'));
    $manager->persist($fighter);
}

for ($i = 0; $i < 12; $i++) {
    $fighter = new Fighter();
    $fighter->setName($faker->name);
    $fighter->setStrength($faker->randomDigit);
    $fighter->setIntelligence($faker->randomDigit);
    $fighter->setPv($faker->randomDigitNotNull);
    $fighter->setType($this->getReference('typeNain'));
    $manager->persist($fighter);
}
*/
        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}
