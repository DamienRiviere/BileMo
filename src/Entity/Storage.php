<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StorageRepository")
 */
class Storage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showStorage"})
     */
    private $capacity;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showStorage"})
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Smartphone", inversedBy="storage")
     * @ORM\JoinColumn(nullable=false)
     */
    private $smartphone;

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
    public function getCapacity(): ?string
    {
        return $this->capacity;
    }

    /**
     * @param string $capacity
     * @return $this
     */
    public function setCapacity(string $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Smartphone|null
     */
    public function getSmartphone(): ?Smartphone
    {
        return $this->smartphone;
    }

    /**
     * @param Smartphone|null $smartphone
     * @return $this
     */
    public function setSmartphone(?Smartphone $smartphone): self
    {
        $this->smartphone = $smartphone;

        return $this;
    }
}
