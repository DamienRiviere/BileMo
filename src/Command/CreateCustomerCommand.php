<?php

namespace App\Command;

use App\Entity\Customer;
use App\Entity\CustomerAddress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class CreateCustomerCommand extends Command
{

    protected static $defaultName = 'app:create-customer';

    /** @var EntityManagerInterface */
    protected $em;

    /** @var UserPasswordEncoderInterface */
    protected $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription("Creates a new customer")
            ->setHelp("This command allows you to create a customer ...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $output->writeln("You are about to create a new customer.");

        $questionEmail = new Question("Enter the email of the customer : ");
        $questionPassword = new Question("Enter the password of the customer : ");
        $questionOrganization = new Question("Enter the organization of the customer : ");

        // Customer
        $email = $helper->ask($input, $output, $questionEmail);
        $password = $helper->ask($input, $output, $questionPassword);
        $organization = $helper->ask($input, $output, $questionOrganization);

        $output->writeln("Now, you are about to create the address of the customer.");

        $questionStreet = new Question("Enter the street of the customer address : ");
        $questionCity = new Question("Enter the city of the customer address : ");
        $questionRegion = new Question("Enter the region of the customer address : ");
        $questionPostalCode = new Question("Enter the postal code of the customer address : ");
        $questionPhoneNumber = new Question("Enter the phone number of the customer address : ");

        // Customer Address
        $street = $helper->ask($input, $output, $questionStreet);
        $city = $helper->ask($input, $output, $questionCity);
        $region = $helper->ask($input, $output, $questionRegion);
        $postalCode = $helper->ask($input, $output, $questionPostalCode);
        $phoneNumber = $helper->ask($input, $output, $questionPhoneNumber);

        $customerAddress = new CustomerAddress();
        $customerAddress
            ->setStreet($street)
            ->setCity($city)
            ->setRegion($region)
            ->setPostalCode($postalCode)
            ->setPhoneNumber($phoneNumber);

        $customer = new Customer();
        $customer
            ->setEmail($email)
            ->setPassword($this->encoder->encodePassword($customer, $password))
            ->setOrganization($organization)
            ->setCustomerSince(new \DateTime())
            ->setAddress($customerAddress);

        $this->em->persist($customer);
        $this->em->flush();

        $output->write("Your customer has been created !");
    }
}
