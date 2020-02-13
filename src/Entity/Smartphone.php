<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SmartphoneRepository")
 */
class Smartphone
{

    public const LIMIT_PER_PAGE = 10;
    public const SHOW_PRODUCT_DETAILS = 'api_product_details';
    public const SHOW_PRODUCTS = 'api_show_products';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @SWG\Property(description="The unique identifier of the smartphone.")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showProduct"})
     * @SWG\Property(type="string", maxLength=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showProduct"})
     * @SWG\Property(type="string", maxLength=255)
     */
    private $os;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showProduct"})
     * @SWG\Property(type="string", maxLength=255)
     */
    private $dimensions;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showProduct"})
     * @SWG\Property(type="string", maxLength=255)
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showProduct"})
     * @SWG\Property(type="string", maxLength=255)
     */
    private $processor;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showProduct"})
     * @SWG\Property(type="string", maxLength=255)
     */
    private $gpu;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showProduct"})
     * @SWG\Property(type="string", maxLength=255)
     */
    private $ram;

    /**
     * @ORM\Column(type="array")
     * @Groups({"showProduct"})
     * @SWG\Property(
     *     type="array",
     *     @SWG\Items(type="string")
     * )
     */
    private $colors = [];

    /**
     * @ORM\Column(type="array")
     * @Groups({"showProduct"})
     * @SWG\Property(
     *     type="array",
     *     @SWG\Items(type="string")
     * )
     */
    private $ports = [];

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Display", inversedBy="smartphone", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"display"})
     * @SWG\Property(ref=@Model(type=Display::class))
     */
    private $display;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Storage", mappedBy="smartphone", orphanRemoval=true, cascade={"persist"})
     * @Groups({"storage"})
     * @SWG\Property(ref=@Model(type=Storage::class))
     */
    private $storage;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Camera", inversedBy="smartphone", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"camera"})
     * @SWG\Property(ref=@Model(type=Camera::class))
     */
    private $camera;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Battery", inversedBy="smartphone", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"battery"})
     * @SWG\Property(ref=@Model(type=Battery::class))
     */
    private $battery;

    /**
     * Smartphone constructor.
     */
    public function __construct()
    {
        $this->storage = new ArrayCollection();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOs(): ?string
    {
        return $this->os;
    }

    /**
     * @param string $os
     * @return $this
     */
    public function setOs(string $os): self
    {
        $this->os = $os;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDimensions(): ?string
    {
        return $this->dimensions;
    }

    /**
     * @param string $dimensions
     * @return $this
     */
    public function setDimensions(string $dimensions): self
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWeight(): ?string
    {
        return $this->weight;
    }

    /**
     * @param string $weight
     * @return $this
     */
    public function setWeight(string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProcessor(): ?string
    {
        return $this->processor;
    }

    /**
     * @param string $processor
     * @return $this
     */
    public function setProcessor(string $processor): self
    {
        $this->processor = $processor;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGpu(): ?string
    {
        return $this->gpu;
    }

    /**
     * @param string $gpu
     * @return $this
     */
    public function setGpu(string $gpu): self
    {
        $this->gpu = $gpu;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRam(): ?string
    {
        return $this->ram;
    }

    /**
     * @param string $ram
     * @return $this
     */
    public function setRam(string $ram): self
    {
        $this->ram = $ram;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getColors(): ?array
    {
        return $this->colors;
    }

    /**
     * @param array $colors
     * @return $this
     */
    public function setColors(array $colors): self
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getPorts(): ?array
    {
        return $this->ports;
    }

    /**
     * @param array $ports
     * @return $this
     */
    public function setPorts(array $ports): self
    {
        $this->ports = $ports;

        return $this;
    }

    /**
     * @return Display|null
     */
    public function getDisplay(): ?Display
    {
        return $this->display;
    }

    /**
     * @param Display $display
     * @return $this
     */
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

    /**
     * @param $storage
     * @return $this
     */
    public function setStorage($storage): self
    {
        $this->storage[] = $storage;

        return $this;
    }

    /**
     * @param Storage $storage
     * @return $this
     */
    public function addStorage(Storage $storage): self
    {
        if (!$this->storage->contains($storage)) {
            $this->storage[] = $storage;
            $storage->setSmartphone($this);
        }

        return $this;
    }

    /**
     * @param Storage $storage
     * @return $this
     */
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

    /**
     * @return Camera|null
     */
    public function getCamera(): ?Camera
    {
        return $this->camera;
    }

    /**
     * @param Camera $camera
     * @return $this
     */
    public function setCamera(Camera $camera): self
    {
        $this->camera = $camera;

        return $this;
    }

    /**
     * @return Battery|null
     */
    public function getBattery(): ?Battery
    {
        return $this->battery;
    }

    /**
     * @param Battery $battery
     * @return $this
     */
    public function setBattery(Battery $battery): self
    {
        $this->battery = $battery;

        return $this;
    }
}
