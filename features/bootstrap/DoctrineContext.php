<?php

declare(strict_types=1);

use App\Domain\Customer\CustomerDTO;
use App\Domain\User\UserDTO;
use App\Entity\Battery;
use App\Entity\Camera;
use App\Entity\Customer;
use App\Entity\CustomerAddress;
use App\Entity\Display;
use App\Entity\Smartphone;
use App\Entity\Storage;
use App\Entity\User;
use App\Entity\UserAddress;
use Behat\Behat\Context\Context;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class DoctrineContext
 */
class DoctrineContext implements Context
{
    /** @var SchemaTool */
    protected $schemaTool;

    /** @var Registry */
    protected $doctrine;

    /** @var KernelInterface */
    protected $kernel;

    /** @var UserPasswordEncoderInterface */
    protected $encoder;

    /**
     * DoctrineContext constructor.
     *
     * @param Registry $doctrine
     * @param KernelInterface $kernel
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        Registry $doctrine,
        KernelInterface $kernel,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->doctrine = $doctrine;
        $this->schemaTool = new SchemaTool($this->doctrine->getManager());
        $this->kernel = $kernel;
        $this->encoder = $encoder;
    }

    /**
     * @BeforeScenario
     *
     * @throws ToolsException
     */
    public function clearDatabase()
    {
        $this->schemaTool->dropSchema($this->doctrine->getManager()->getMetadataFactory()->getAllMetadata());
        $this->schemaTool->createSchema($this->doctrine->getManager()->getMetadataFactory()->getAllMetadata());
    }

    /**
     * @return ObjectManager
     */
    public function getManager()
    {
        return $this->doctrine->getManager();
    }

    /**
     * @Given I load following file :arg1
     *
     * @param string $file
     * @throws Exception
     */
    public function iLoadFollowingFile(string $file)
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__ . '/../fixtures/' . $file);

        foreach ($objectSet->getObjects() as $object) {
            if ($object instanceof CustomerDTO) {
                $this->saveCustomerAndUser($object);
            }

            if ($object instanceof Smartphone) {
                $this->createSmartphone($object);
            }
        }

        $this->getManager()->flush();
    }

    public function saveCustomerAndUser(CustomerDTO $object)
    {
        $customer = $this->createCustomer($object);

        for ($i = 0; $i < 50; $i++) {
            $user = $this->getUser();

            $customer->addUser($user);
            $user->setCustomer($customer);

            $this->getManager()->persist($customer);
            $this->getManager()->persist($user);
        }
    }

    public function createCustomer(CustomerDTO $object)
    {
        $customer = new Customer();
        $customer
            ->setPassword($this->encoder->encodePassword($customer, $object->getPassword()))
            ->setEmail($object->getPassword())
            ->setCustomerSince(new DateTime())
            ->setOrganization($object->getOrganization())
        ;

        $customerAddress = $this->createCustomerAddress($object);

        $customer->setAddress($customerAddress);

        return $customer;
    }

    public function createCustomerAddress(CustomerDTO $object)
    {
        $customerAddress = new CustomerAddress();
        $customerAddress
            ->setPhoneNumber($object->getPhoneNumber())
            ->setPostalCode($object->getPostalCode())
            ->setRegion($object->getRegion())
            ->setCity($object->getCity())
            ->setStreet($object->getStreet())
        ;

        return $customerAddress;
    }

    public function getUser()
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__ . '/../fixtures/user.yaml');

        foreach ($objectSet->getObjects() as $object) {
            $user = $this->createUser($object);
            $userAddress = $this->createUserAddress($object);
            $user->addAddress($userAddress);

            return $user;
        }
    }

    public function createUser(UserDTO $object)
    {
        $user = new User();
        $user
            ->setEmail($object->getEmail())
            ->setFirstName($object->getFirstName())
            ->setLastName($object->getLastName())
        ;

        return $user;
    }

    public function createUserAddress(UserDTO $object)
    {
        $userAddress = new UserAddress();
        $userAddress
            ->setStreet($object->getStreet())
            ->setCity($object->getCity())
            ->setRegion($object->getRegion())
            ->setPhoneNumber((int)$object->getPhoneNumber())
            ->setPostalCode((int)$object->getPostalCode())
        ;

        return $userAddress;
    }

    public function createSmartphone(Smartphone $object)
    {
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

        $display = $this->createDisplay($object->getDisplay());
        $storage = $this->createStorage($object->getStorage()[0]);
        $camera = $this->createCamera($object->getCamera());
        $battery = $this->createBattery($object->getBattery());

        $smartphone
            ->setDisplay($display)
            ->setCamera($camera)
            ->setBattery($battery)
            ->addStorage($storage)
        ;

        $this->getManager()->persist($smartphone);
    }

    public function createDisplay(Display $object)
    {
        $display = new Display();
        $display
            ->setSize($object->getSize())
            ->setType($object->getType())
            ->setResolution($object->getResolution())
        ;

        return $display;
    }

    public function createStorage(Storage $object)
    {
        $storage = new Storage();
        $storage
            ->setCapacity($object->getCapacity())
            ->setPrice($object->getPrice())
        ;

        return $storage;
    }

    public function createCamera(Camera $object)
    {
        $camera = new Camera();
        $camera
            ->setMegapixels($object->getMegapixels())
        ;

        return $camera;
    }

    public function createBattery(Battery $object)
    {
        $battery = new Battery();
        $battery
            ->setCapacity($object->getCapacity())
            ->setFastCharge($object->getFastCharge())
            ->setWirelessCharging($object->getWirelessCharging())
            ->setRemovableBattery($object->getRemovableBattery())
            ->setBatteryTechnology($object->getBatteryTechnology())
        ;

        return $battery;
    }
}
