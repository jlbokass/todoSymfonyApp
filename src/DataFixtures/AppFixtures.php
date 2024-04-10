<?php

namespace App\DataFixtures;

use App\Entity\Listing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 11; $i++) {
            $listing = new Listing();
            $listing->setName("This is my awesome listing number $i");
            $manager->persist($listing);
        }

        $manager->flush();
    }
}
