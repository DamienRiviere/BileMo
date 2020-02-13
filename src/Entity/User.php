<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User
{

    public const LIMIT_PER_PAGE = 10;
    public const SHOW_USER_DETAILS = 'api_show_user_details';
    public const SHOW_USERS = 'api_show_users';
    public const DELETE_USER = 'api_delete_user';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showUser"})
     * @SWG\Property(type="string", maxLength=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showUser"})
     * @SWG\Property(type="string", maxLength=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showUser"})
     * @SWG\Property(type="string", maxLength=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showUser"})
     * @SWG\Property(type="string", maxLength=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"showUser"})
     * @SWG\Property(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserAddress", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    public function __construct()
    {
        $this->address = new ArrayCollection();
    }

    /**
     * Initialize slug when the trick is created
     * @ORM\PrePersist
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $slug = $this->firstName . " " . $this->getLastName();
            $this->slug = $slugify->slugify($slug);
        }
    }

    /**
     * Initialize date when the trick is created
     * @ORM\PrePersist
     * @throws \Exception
     */
    public function initializeDate()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \Datetime();
        }
    }

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
    public function getFirstName(): ?string
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
     * @return string|null
     */
    public function getLastName(): ?string
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
     * @return string|null
     */
    public function getEmail(): ?string
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
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|UserAddress[]
     */
    public function getAddress(): Collection
    {
        return $this->address;
    }

    /**
     * @param UserAddress $address
     * @return $this
     */
    public function addAddress(UserAddress $address): self
    {
        if (!$this->address->contains($address)) {
            $this->address[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    /**
     * @param UserAddress $address
     * @return $this
     */
    public function removeAddress(UserAddress $address): self
    {
        if ($this->address->contains($address)) {
            $this->address->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

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
     * @param Customer|null $customer
     * @return $this
     */
    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
