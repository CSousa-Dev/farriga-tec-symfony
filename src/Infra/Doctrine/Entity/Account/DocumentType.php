<?php

namespace App\Infra\Doctrine\Entity\Account;

use Doctrine\ORM\Mapping as ORM;
use App\Infra\Doctrine\Repository\Account\DocumentTypeRepository;

#[ORM\Entity(repositoryClass: DocumentTypeRepository::class)]
#[ORM\Table(name: 'account.document_type')]
class DocumentType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
