<?php

namespace App\Domain\Customer;

use App\Entity\Customer;
use App\Entity\CustomerAddress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ResolverCustomer
 * @package App\Domain\Customer
 */
final class ResolverCustomer
{

    /** @var EntityManagerInterface */
    protected $em;

    /** @var UserPasswordEncoderInterface */
    protected $encoder;

    /**
     * ResolverCustomer constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * @param Customer $customer
     * @param CustomerAddress $customerAddress
     */
    public function save(Customer $customer, CustomerAddress $customerAddress)
    {
        $customer->setAddress($customerAddress);

        $this->em->persist($customer);
        $this->em->flush();
    }

    /**
     * @param CustomerDTO $dto
     * @return Customer
     * @throws \Exception
     */
    public function createCustomer(CustomerDTO $dto): Customer
    {
        $customer = new Customer();
        $customer
            ->setEmail($dto->getEmail())
            ->setPassword($this->encoder->encodePassword($customer, $dto->getPassword()))
            ->setOrganization($dto->getOrganization())
            ->setCustomerSince(new \DateTime())
            ->setRoles("ROLE_USER")
        ;

        return $customer;
    }

    /**
     * @param CustomerDTO $dto
     * @return CustomerAddress
     */
    public function createCustomerAddress(CustomerDTO $dto): CustomerAddress
    {
        $customerAddress = new CustomerAddress();
        $customerAddress
            ->setStreet($dto->getStreet())
            ->setCity($dto->getCity())
            ->setRegion($dto->getRegion())
            ->setPostalCode($dto->getPostalCode())
            ->setPhoneNumber($dto->getPhoneNumber())
        ;

        return $customerAddress;
    }

    /**
     * @param array $data
     * @return CustomerDTO
     */
    public function createCustomerDTO(array $data): CustomerDTO
    {
        $dto = new CustomerDTO();
        $dto
            ->setEmail($data['email'])
            ->setPassword($data['password'])
            ->setOrganization($data['organization'])
            ->setStreet($data['street'])
            ->setCity($data['city'])
            ->setRegion($data['region'])
            ->setPostalCode($data['postalCode'])
            ->setPhoneNumber($data['phoneNumber'])
        ;

        return $dto;
    }
}
