<?php

namespace App\Domain\User;

use Symfony\Component\Validator\Constraints as Assert;
use App\Domain\Common\Validators\UniqueEntityInput;

/**
 * Class UserDTO
 * @package App\Domain\User
 * @UniqueEntityInput(
 *      class="App\Entity\User",
 *      fields={"email"},
 *      message="Cette adresse email est déjà existante, veuillez en choisir une autre !",
 * )
 */
final class UserDTO
{

    /**
     * @var string
     * @Assert\NotBlank(
     *     message="Votre prénom ne doit pas être vide !"
     * )
     */
    protected $firstName;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message="Votre nom ne doit pas être vide !"
     * )
     */
    protected $lastName;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message="Votre email ne doit pas être vide !"
     * )
     * @Assert\Email(
     *     message="Votre email n'est pas valide !"
     * )
     */
    protected $email;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message="Votre rue ne doit pas être vide !"
     * )
     */
    protected $street;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message="Votre ville ne doit pas être vide !"
     * )
     */
    protected $city;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message="Votre region ne doit pas être vide !"
     * )
     */
    protected $region;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message="Votre code postal ne doit pas être vide !"
     * )
     */
    protected $postalCode;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message="Votre numéro de téléphone ne doit pas être vide !"
     * )
     */
    protected $phoneNumber;

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getStreet(): string
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
     * @return string
     */
    public function getCity(): string
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
     * @return string
     */
    public function getRegion(): string
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
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     * @return $this
     */
    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
