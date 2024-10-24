<?php

namespace App\Infra\Doctrine\Entity\Devices;

use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;
use App\Infra\Doctrine\Repository\Devices\SensorTypesRepository;

#[ORM\Entity(repositoryClass: SensorTypesRepository::class)]
#[Table(name: 'devices.sensor_types')]
class SensorTypes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
