<?php

namespace App\DataFixtures;

use App\Entity\Battery;
use App\Entity\Camera;
use App\Entity\Display;
use App\Entity\Smartphone;
use App\Entity\Storage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__ . "/smartphone.yaml")->getObjects();
        foreach ($objectSet as $object) {
            if ($object instanceof Smartphone) {
                $smartphone = new Smartphone();
                $smartphone
                    ->setName($object->getName())
                    ->setOs($object->getOs())
                    ->setDimensions($object->getDimensions())
                    ->setWeight($object->getWeight())
                    ->setProcessor($object->getProcessor())
                    ->setGpu($object->getGpu())
                    ->setRam($object->getRam())
                    ->setColors($object->getColors())
                    ->setPorts($object->getPorts())
                ;

                $manager->persist($smartphone);

                $objectBattery = $object->getBattery();
                $battery = new Battery();
                $battery
                    ->setCapacity($objectBattery->getCapacity())
                    ->setBatteryTechnology($objectBattery->getBatteryTechnology())
                    ->setRemovableBattery($objectBattery->getRemovableBattery())
                    ->setWirelessCharging($objectBattery->getWirelessCharging())
                    ->setFastCharge($objectBattery->getFastCharge())
                    ->setSmartphone($smartphone)
                ;

                $manager->persist($battery);

                $objectCamera = $object->getCamera();
                $camera = new Camera();
                $camera
                    ->setMegapixels($objectCamera->getMegapixels())
                    ->setSmartphone($smartphone)
                ;

                $manager->persist($camera);

                $objectDisplay = $object->getDisplay();
                $display = new Display();
                $display
                    ->setResolution($objectDisplay->getResolution())
                    ->setSize($objectDisplay->getSize())
                    ->setType($objectDisplay->getType())
                    ->setSmartphone($smartphone)
                ;

                $manager->persist($display);

                $objectStorage = $object->getStorage();
                foreach ($objectStorage as $storages) {
                    $storage = new Storage();
                    $storage
                        ->setCapacity($storages->getCapacity())
                        ->setPrice($storages->getPrice())
                        ->setSmartphone($smartphone)
                    ;
                    $manager->persist($storage);
                }
            }
        }
        $manager->flush();
    }
}
