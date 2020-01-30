<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DisplayRepository")
 */
class Display
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
    private $size;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $resolution;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Smartphone", mappedBy="display", cascade={"persist", "remove"})
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
    public function getSize(): ?string
    {
        return $this->size;
    }

    /**
     * @param string $size
     * @return $this
     */
    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResolution(): ?string
    {
        return $this->resolution;
    }

    /**
     * @param string $resolution
     * @return $this
     */
    public function setResolution(string $resolution): self
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

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
        if ($smartphone->getDisplay() !== $this) {
            $smartphone->setDisplay($this);
        }

        return $this;
    }
}
