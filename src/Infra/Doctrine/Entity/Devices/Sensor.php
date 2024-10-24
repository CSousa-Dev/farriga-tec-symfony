<?php

namespace App\Infra\Doctrine\Entity\Devices;

use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;
use App\Infra\Doctrine\Repository\Devices\SensorRepository;

#[ORM\Entity(repositoryClass: SensorRepository::class)]
#[Table(name: 'devices.sensor')]
class Sensor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?SensorTypes $type = null;

    #[ORM\Column]
    private ?bool $usage = null;

    #[ORM\Column(length: 255)]
    private ?string $treshold = null;

    #[ORM\ManyToOne(inversedBy: 'Sensors')]
    private ?Device $device = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?SensorTypes
    {
        return $this->type;
    }

    public function setType(?SensorTypes $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function isUsage(): ?bool
    {
        return $this->usage;
    }

    public function setUsage(bool $usage): static
    {
        $this->usage = $usage;

        return $this;
    }

    public function getTreshold(): ?string
    {
        return $this->treshold;
    }

    public function setTreshold(string $treshold): static
    {
        $this->treshold = $treshold;

        return $this;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): static
    {
        $this->device = $device;

        return $this;
    }
}
