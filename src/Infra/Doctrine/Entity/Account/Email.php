<?php

namespace App\Infra\Doctrine\Entity\Account;

use Doctrine\ORM\Mapping as ORM;
use App\Infra\Doctrine\Repository\Account\EmailRepository;

#[ORM\Entity(repositoryClass: EmailRepository::class)]
#[ORM\Table(name: 'account.email')]
class Email
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $validatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $validationCode = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $validationCodeCreatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $validationCodeSentAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getValidatedAt(): ?\DateTimeImmutable
    {
        return $this->validatedAt;
    }

    public function setValidatedAt(?\DateTimeImmutable $validatedAt): static
    {
        $this->validatedAt = $validatedAt;

        return $this;
    }

    public function getValidationCode(): ?string
    {
        return $this->validationCode;
    }

    public function setValidationCode(?string $validationCode): static
    {
        $this->validationCode = $validationCode;

        return $this;
    }

    public function getValidationCodeCreatedAt(): ?\DateTimeImmutable
    {
        return $this->validationCodeCreatedAt;
    }

    public function setValidationCodeCreatedAt(\DateTimeImmutable $validationCodeCreatedAt): static
    {
        $this->validationCodeCreatedAt = $validationCodeCreatedAt;

        return $this;
    }

    public function getValidationCodeSentAt(): ?\DateTimeImmutable
    {
        return $this->validationCodeSentAt;
    }

    public function setValidationCodeSentAt(\DateTimeImmutable $validationCodeSentAt): static
    {
        $this->validationCodeSentAt = $validationCodeSentAt;

        return $this;
    }
}
