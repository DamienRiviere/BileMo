<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BatteryRepository")
 */
class Battery
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
    private $capacity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $batteryTechnology;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $removableBattery;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wirelessCharging;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fastCharge;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Smartphone", mappedBy="battery", cascade={"persist", "remove"})
     */
    private $smartphone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapacity(): ?string
    {
        return $this->capacity;
    }

    public function setCapacity(string $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getBatteryTechnology(): ?string
    {
        return $this->batteryTechnology;
    }

    public function setBatteryTechnology(string $batteryTechnology): self
    {
        $this->batteryTechnology = $batteryTechnology;

        return $this;
    }

    public function getRemovableBattery(): ?string
    {
        return $this->removableBattery;
    }

    public function setRemovableBattery(string $removableBattery): self
    {
        $this->removableBattery = $removableBattery;

        return $this;
    }

    public function getWirelessCharging(): ?string
    {
        return $this->wirelessCharging;
    }

    public function setWirelessCharging(string $wirelessCharging): self
    {
        $this->wirelessCharging = $wirelessCharging;

        return $this;
    }

    public function getFastCharge(): ?string
    {
        return $this->fastCharge;
    }

    public function setFastCharge(string $fastCharge): self
    {
        $this->fastCharge = $fastCharge;

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
        if ($smartphone->getBattery() !== $this) {
            $smartphone->setBattery($this);
        }

        return $this;
    }
}
