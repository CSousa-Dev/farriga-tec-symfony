<?php

namespace App\Infra\Doctrine\Entity\Devices;

use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;
use App\Infra\Doctrine\Entity\Account\User;
use Doctrine\Common\Collections\Collection;
use App\Infra\Doctrine\Entity\Devices\Sensor;
use Doctrine\Common\Collections\ArrayCollection;
use App\Infra\Doctrine\Repository\Devices\DeviceRepository;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
#[Table(name: 'devices.device')]
class Device
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alias = null;

    #[ORM\Column(length: 255)]
    private ?string $macAddress = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $irrigatedAt = null;

    /**
     * @var Collection<int, Sensor>
     */
    #[ORM\OneToMany(targetEntity: Sensor::class, mappedBy: 'device')]
    private Collection $Sensors;

    #[ORM\ManyToOne(inversedBy: 'devices')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $owner = null;

    public function __construct()
    {
        $this->Sensors = new ArrayCollection();
    }

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

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): static
    {
        $this->alias = $alias;

        return $this;
    }

    public function getMacAddress(): ?string
    {
        return $this->macAddress;
    }

    public function setMacAddress(string $macAddress): static
    {
        $this->macAddress = $macAddress;

        return $this;
    }

    public function getIrrigatedAt(): ?\DateTimeImmutable
    {
        return $this->irrigatedAt;
    }

    public function setIrrigatedAt(?\DateTimeImmutable $irrigatedAt): static
    {
        $this->irrigatedAt = $irrigatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Sensor>
     */
    public function getSensors(): Collection
    {
        return $this->Sensors;
    }

    public function addSensor(Sensor $sensor): static
    {
        if (!$this->Sensors->contains($sensor)) {
            $this->Sensors->add($sensor);
            $sensor->setDevice($this);
        }

        return $this;
    }

    public function removeSensor(Sensor $sensor): static
    {
        if ($this->Sensors->removeElement($sensor)) {
            // set the owning side to null (unless already changed)
            if ($sensor->getDevice() === $this) {
                $sensor->setDevice(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
