<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CameraRepository")
 */
class Camera
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showProductsDetails"})
     */
    private $megapixels;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Smartphone", mappedBy="camera", cascade={"persist", "remove"})
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
    public function getMegapixels(): ?string
    {
        return $this->megapixels;
    }

    /**
     * @param string $megapixels
     * @return $this
     */
    public function setMegapixels(string $megapixels): self
    {
        $this->megapixels = $megapixels;

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
     * @param Smartphone $smartphone
     * @return $this
     */
    public function setSmartphone(Smartphone $smartphone): self
    {
        $this->smartphone = $smartphone;

        // set the owning side of the relation if necessary
        if ($smartphone->getCamera() !== $this) {
            $smartphone->setCamera($this);
        }

        return $this;
    }
}
