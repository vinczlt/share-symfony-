<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $Sujet = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Message = null;

    #[ORM\Column]
    private ?\DateTime $dateEnvoi = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->Sujet;
    }

    public function setSujet(string $Sujet): static
    {
        $this->Sujet = $Sujet;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->Message;
    }

    public function setMessage(string $Message): static
    {
        $this->Message = $Message;

        return $this;
    }

    public function getDateEnvoi(): ?\DateTime
    {
        return $this->dateEnvoi;
    }

    public function setDateEnvoi(\DateTime $dateEnvoi): static
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }
}
