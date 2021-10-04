<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->encoder = $hasher;
    }
    public function load(ObjectManager $manager)
    {
        $adminUser = new Employee();
        $adminUser->setRoles(['ROLE_RH']);
        $adminUser->setEmail('rh@humanbooster.com');
        $adminUser->setPassword($this->encoder->hashPassword($adminUser, 'rh123@'));
        $adminUser->setEmployeeFirstName('admin');
        $adminUser->setEmployeeName('admin');
        $adminUser->setEmployeePhoto('admin');
        $adminUser->setSector($this->getReference('RH'));
        $adminUser->setContract($this->getReference('CDI'));

        $manager->persist($adminUser);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SectorFixture::class,
            ContractFixture::class,
        ];
    }
}
