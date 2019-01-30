<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AddressesFixtures extends Fixture implements DependentFixtureInterface
{
    public const ADD_REFERENCE = 'add';

    public function load(ObjectManager $manager)
    {
        $address = new Address();
        $address->setUser($this->getReference(UserFixtures::USER_REFERENCE));
        $address->setNumber('21');
        $address->setStreetname("rue de Test");
        $address->setZipcode("75012");
        $address->setCity("Paris");
        $address->setCountry("France");
        $this->addReference(self::ADD_REFERENCE, $address);

        $manager->persist($address);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
