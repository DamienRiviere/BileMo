<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SmartphoneRepository")
 */
class Smartphone
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $os;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dimensions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $processor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ram;

    /**
     * @ORM\Column(type="array")
     */
    private $colors = [];

    /**
     * @ORM\Column(type="array")
     */
    private $ports = [];

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Display", inversedBy="smartphone", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $display;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Storage", mappedBy="smartphone", orphanRemoval=true, cascade={"persist"})
     */
    private $storage;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Camera", inversedBy="smartphone", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $camera;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Battery", inversedBy="smartphone", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $battery;

    public function __construct()
    {
        $this->storage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOs(): ?string
    {
        return $this->os;
    }

    public function setOs(string $os): self
    {
        $this->os = $os;

        return $this;
    }

    public function getDimensions(): ?string
    {
        return $this->dimensions;
    }

    public function setDimensions(string $dimensions): self
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getProcessor(): ?string
    {
        return $this->processor;
    }

    public function setProcessor(string $processor): self
    {
        $this->processor = $processor;

        return $this;
    }

    public function getGpu(): ?string
    {
        return $this->gpu;
    }

    public function setGpu(string $gpu): self
    {
        $this->gpu = $gpu;

        return $this;
    }

    public function getRam(): ?string
    {
        return $this->ram;
    }

    public function setRam(string $ram): self
    {
        $this->ram = $ram;

        return $this;
    }

    public function getColors(): ?array
    {
        return $this->colors;
    }

    public function setColors(array $colors): self
    {
        $this->colors = $colors;

        return $this;
    }

    public function getPorts(): ?array
    {
        return $this->ports;
    }

    public function setPorts(array $ports): self
    {
        $this->ports = $ports;

        return $this;
    }

    public function getDisplay(): ?Display
    {
        return $this->display;
    }

    public function setDisplay(Display $display): self
    {
        $this->display = $display;

        return $this;
    }

    /**
     * @return Collection|Storage[]
     */
    public function getStorage(): Collection
    {
        return $this->storage;
    }

    public function setStorage($storage): self
    {
        $this->storage[] = $storage;

        return $this;
    }

    public function addStorage(Storage $storage): self
    {
        if (!$this->storage->contains($storage)) {
            $this->storage[] = $storage;
            $storage->setSmartphone($this);
        }

        return $this;
    }

    public function removeStorage(Storage $storage): self
    {
        if ($this->storage->contains($storage)) {
            $this->storage->removeElement($storage);
            // set the owning side to null (unless already changed)
            if ($storage->getSmartphone() === $this) {
                $storage->setSmartphone(null);
            }
        }

        return $this;
    }

    public function getCamera(): ?Camera
    {
        return $this->camera;
    }

    public function setCamera(Camera $camera): self
    {
        $this->camera = $camera;

        return $this;
    }

    public function getBattery(): ?Battery
    {
        return $this->battery;
    }

    public function setBattery(Battery $battery): self
    {
        $this->battery = $battery;

        return $this;
    }
}
