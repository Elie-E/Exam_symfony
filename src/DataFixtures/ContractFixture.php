<?php

namespace App\DataFixtures;

use App\Entity\Contract;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContractFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $contractList = ['CDI', 'CDD', 'Interim'];

        forEach($contractList as $contractName){
            $contract = new Contract();
            $contract->setContractType($contractName);
            $manager->persist($contract);
            $this->addReference($contractName, $contract);
        }

        $manager->flush();
    }
}
