<?php

declare(strict_types=1);

use App\Entity\Customer;
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
     * @Then the following entity :arg1 should have :arg2 entry into database
     *
     * @param string $entity
     * @param        $number
     *
     * @throws Exception
     */
    public function theFollowingEntityShouldHaveEntryIntoDatabase(string $entity, $number)
    {
        $totalEntries = $this->getManager()->getRepository($entity)->findAll();

        if (count($totalEntries) !== (int) $number) {
            throw new Exception(
                sprintf("'%s' entry expected, '%s' found", $number, count($totalEntries))
            );
        }
    }

    /**
     * @Given I load following file :arg1
     *
     * @param string $file
     */
    public function iLoadFollowingFile(string $file)
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__ . '/../fixtures/' . $file);

        foreach ($objectSet->getObjects() as $object) {
            if ($object instanceof Customer) {
                $customer = new Customer();
                $customer
                    ->setPassword($this->encoder->encodePassword($customer, $object->getPassword()))
                    ->setEmail($object->getEmail())
                    ->setCustomerSince($object->getCustomerSince())
                    ->setOrganization($object->getOrganization())
                    ->setAddress($object->getAddress());


                $this->getManager()->persist($customer);
            } else {
                $this->getManager()->persist($object);
            }
        }

        $this->getManager()->flush();
    }

    /**
     * @param AbstractEntity $entity
     * @param string         $uuid
     *
     * @throws ReflectionException
     */
    private function setUuid(AbstractEntity $entity, string $uuid)
    {
        $reflection = new \ReflectionClass($entity);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($entity, $uuid);
    }

    /**
     * @Given object :classname with property :property as value :value should have following id :targetId
     */
    public function objectWithPropertyAsValueShouldHaveFollowingId($classname, $property, $value, $targetId)
    {
        /** @var AbstractEntity $entity */
        $entity = $this->getManager()->getRepository($classname)
            ->findOneBy(
                [
                    $property => $value
                ]
            );
        $this->setUuid($entity, $targetId);
        $this->getManager()->flush();
    }

    /**
     * @Then object on entity :arg1 with property :arg2 and value :arg3 should be exist
     */
    public function objectOnEntityWithPropertyAndValueShouldBeExist($arg1, $arg2, $arg3)
    {
        $entity = $this->doctrine->getRepository($arg1)
            ->findOneBy(
                [
                    $arg2 => $arg3,
                ]
            );

        if (is_null($entity)) {
            throw new Exception('Object should be exist');
        }
    }
}
