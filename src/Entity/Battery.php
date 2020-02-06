<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @Groups({"showBattery"})
     */
    private $capacity;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showBattery"})
     */
    private $batteryTechnology;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showBattery"})
     */
    private $removableBattery;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showBattery"})
     */
    private $wirelessCharging;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"showBattery"})
     */
    private $fastCharge;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Smartphone", mappedBy="battery", cascade={"persist", "remove"})
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
    public function getBatteryTechnology(): ?string
    {
        return $this->batteryTechnology;
    }

    /**
     * @param string $batteryTechnology
     * @return $this
     */
    public function setBatteryTechnology(string $batteryTechnology): self
    {
        $this->batteryTechnology = $batteryTechnology;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRemovableBattery(): ?string
    {
        return $this->removableBattery;
    }

    /**
     * @param string $removableBattery
     * @return $this
     */
    public function setRemovableBattery(string $removableBattery): self
    {
        $this->removableBattery = $removableBattery;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWirelessCharging(): ?string
    {
        return $this->wirelessCharging;
    }

    /**
     * @param string $wirelessCharging
     * @return $this
     */
    public function setWirelessCharging(string $wirelessCharging): self
    {
        $this->wirelessCharging = $wirelessCharging;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFastCharge(): ?string
    {
        return $this->fastCharge;
    }

    /**
     * @param string $fastCharge
     * @return $this
     */
    public function setFastCharge(string $fastCharge): self
    {
        $this->fastCharge = $fastCharge;

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
        if ($smartphone->getBattery() !== $this) {
            $smartphone->setBattery($this);
        }

        return $this;
    }
}
