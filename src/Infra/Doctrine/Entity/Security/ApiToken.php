<?php

namespace App\Infra\Doctrine\Entity\Security;

use Doctrine\ORM\Mapping as ORM;
use App\Infra\Doctrine\Entity\Security\UserSecurityInfo;
use App\Infra\Doctrine\Repository\Security\ApiTokenRepository;

#[ORM\Entity(repositoryClass: ApiTokenRepository::class)]
#[ORM\Table(name: 'security.api_token')]
class ApiToken
{
    private const PERSONAL_ACCESS_TOKEN_PREFIX = 'tcp_';

    //TODO pensar e definir escopos

    public function __construct(string $type = self::PERSONAL_ACCESS_TOKEN_PREFIX)
    {
        $this->token = $type . bin2hex(random_bytes(32));
        $this->expiresAt = new \DateTimeImmutable('+1 hour');
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'apiToken', cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserSecurityInfo $ownedBy = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expiresAt = null;

    #[ORM\Column(length: 68)]
    private string $token;

    #[ORM\Column]
    private array $scope = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwnedBy(): ?UserSecurityInfo
    {
        return $this->ownedBy;
    }

    public function setOwnedBy(?UserSecurityInfo $ownedBy): static
    {
        $this->ownedBy = $ownedBy;

        return $this;
    }

    public function isValid(): bool
    {
        return $this->expiresAt >= new \DateTimeImmutable();
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getScope(): array
    {
        return $this->scope;
    }

    public function setScope(array $scope): static
    {
        $this->scope = $scope;

        return $this;
    }
}
