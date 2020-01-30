<?php

namespace App\Command;

use App\Domain\Customer\ResolverCustomer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class CreateCustomerCommand extends Command
{

    /** @var string  */
    protected static $defaultName = 'app:create-customer';

    /** @var EntityManagerInterface */
    protected $em;

    /** @var UserPasswordEncoderInterface */
    protected $encoder;

    /** @var ResolverCustomer */
    protected $resolverCustomer;

    /**
     * CreateCustomerCommand constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param ResolverCustomer $resolverCustomer
     */
    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder,
        ResolverCustomer $resolverCustomer
    ) {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->resolverCustomer = $resolverCustomer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription("Creates a new customer")
            ->setHelp("This command allows you to create a customer ...")
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \Exception
     */
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

        $data = [
            "email" => $email,
            "street" => $street,
            "password" => $password,
            "organization" => $street,
            "city" => $city,
            "region" => $region,
            "postalCode" => $postalCode,
            "phoneNumber" => $phoneNumber
        ];

        $dto = $this->resolverCustomer->createCustomerDTO($data);
        $customer = $this->resolverCustomer->createCustomer($dto);
        $customerAddress = $this->resolverCustomer->createCustomerAddress($dto);
        $this->resolverCustomer->save($customer, $customerAddress);

        $output->write("Your customer has been created !");
    }
}
