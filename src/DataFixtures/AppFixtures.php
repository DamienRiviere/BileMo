<?php

namespace App\DataFixtures;

use App\Entity\Battery;
use App\Entity\Camera;
use App\Entity\Customer;
use App\Entity\CustomerAddress;
use App\Entity\Display;
use App\Entity\Smartphone;
use App\Entity\Storage;
use App\Entity\User;
use App\Entity\UserAddress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /** @var UserPasswordEncoderInterface */
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        $damien = new Customer();
        $damien
            ->setEmail("riviere.damien64@gmail.com")
            ->setPassword($this->encoder->encodePassword($damien, 'password'))
            ->setOrganization("DRE")
            ->setRoles("ROLE_USER")
            ->setCustomerSince(new \DateTime())
        ;

        $customerAddress1 = new CustomerAddress();
        $customerAddress1
            ->setStreet($faker->streetAddress)
            ->setCity($faker->city)
            ->setRegion($faker->state)
            ->setPostalCode((int)$faker->postcode)
            ->setPhoneNumber(79548)
            ->setCustomer($damien)
        ;

        for ($u = 0; $u < 50; $u++) {
            $user1 = new User();
            $user1
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setCreatedAt(new \DateTime())
                ->setCustomer($damien)
            ;

            $userAddress1 = new UserAddress();
            $userAddress1
                ->setStreet($faker->streetAddress)
                ->setCity($faker->city)
                ->setRegion($faker->state)
                ->setPostalCode((int)$faker->postcode)
                ->setPhoneNumber(79548)
                ->setUser($user1)
            ;

            $manager->persist($userAddress1);
            $manager->persist($user1);
        }

        $manager->persist($damien);

        for ($i = 0; $i < 50; $i++) {
            $smartphone = new Smartphone();
            $smartphone
                ->setName($faker->sentence(1))
                ->setOs($faker->sentence(1))
                ->setDimensions($faker->sentence(3))
                ->setWeight($faker->sentence(3))
                ->setProcessor($faker->sentence(1))
                ->setGpu($faker->sentence(1))
                ->setRam($faker->sentence(1))
                ->setColors([$faker->sentence(1), $faker->sentence(1)])
                ->setPorts([$faker->sentence(1), $faker->sentence(1)])
            ;

            $battery = new Battery();
            $battery
                ->setCapacity($faker->sentence(3))
                ->setBatteryTechnology($faker->sentence(2))
                ->setRemovableBattery($faker->sentence(1))
                ->setWirelessCharging($faker->sentence(1))
                ->setFastCharge($faker->sentence(1))
                ->setSmartphone($smartphone)
            ;

            $camera = new Camera();
            $camera
                ->setMegapixels($faker->sentence(3))
                ->setSmartphone($smartphone)
            ;

            $display = new Display();
            $display
                ->setResolution($faker->sentence(4))
                ->setSize($faker->sentence(4))
                ->setType($faker->sentence(2))
                ->setSmartphone($smartphone)
            ;

            for ($s = 0; $s < 3; $s++) {
                $storage = new Storage();
                $storage
                    ->setCapacity($faker->sentence(2))
                    ->setPrice($faker->numberBetween(50, 1500))
                ;

                $smartphone->addStorage($storage);
            }

            $manager->persist($smartphone);
        }

        for ($i = 0; $i < 5; $i++) {
            $customer = new Customer();
            $customer
                ->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($customer, 'password'))
                ->setOrganization($faker->company)
                ->setRoles("ROLE_USER")
                ->setCustomerSince(new \DateTime())
            ;

            $customerAddress = new CustomerAddress();
            $customerAddress
                ->setStreet($faker->streetAddress)
                ->setCity($faker->city)
                ->setRegion($faker->state)
                ->setPostalCode((int)$faker->postcode)
                ->setPhoneNumber(79548)
                ->setCustomer($customer)
            ;

            for ($u = 0; $u < 50; $u++) {
                $user = new User();
                $user
                    ->setFirstName($faker->firstName)
                    ->setLastName($faker->lastName)
                    ->setEmail($faker->email)
                    ->setCreatedAt(new \DateTime())
                    ->setCustomer($customer)
                ;

                $userAddress = new UserAddress();
                $userAddress
                    ->setStreet($faker->streetAddress)
                    ->setCity($faker->city)
                    ->setRegion($faker->state)
                    ->setPostalCode((int)$faker->postcode)
                    ->setPhoneNumber(79548)
                    ->setUser($user)
                ;

                $manager->persist($userAddress);
                $manager->persist($user);
            }

            $manager->persist($customer);
        }

        $manager->flush();
    }
}
