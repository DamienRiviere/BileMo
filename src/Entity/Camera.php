<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $megapixels;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Smartphone", mappedBy="camera", cascade={"persist", "remove"})
     */
    private $smartphone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMegapixels(): ?string
    {
        return $this->megapixels;
    }

    public function setMegapixels(string $megapixels): self
    {
        $this->megapixels = $megapixels;

        return $this;
    }

    public function getSmartphone(): ?Smartphone
    {
        return $this->smartphone;
    }

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
