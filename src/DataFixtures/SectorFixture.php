<?php

namespace App\DataFixtures;

use App\Entity\Sector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SectorFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sectorList = ['RH', 'Informatique', 'Comptabilité', 'Direction'];

        forEach($sectorList as $sectorName){
            $sector = new Sector();
            $sector->setSectorName($sectorName);
            $manager->persist($sector);
            $this->addReference($sectorName, $sector);
        }

        $manager->flush();
    }
}
