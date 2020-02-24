<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerAddressRepository")
 */
class CustomerAddress
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $region;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $phoneNumber;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Customer", mappedBy="address", cascade={"persist", "remove"})
     */
    private $customer;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return $this
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return $this
     */
    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    /**
     * @param int $postalCode
     * @return $this
     */
    public function setPostalCode(int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    /**
     * @param int $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return $this
     */
    public function setCustomer(Customer $customer): self
    {
        $this->customer = $customer;

        // set the owning side of the relation if necessary
        if ($customer->getAddress() !== $this) {
            $customer->setAddress($this);
        }

        return $this;
    }
}
