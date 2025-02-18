<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/*#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_rep = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?Reclamation $reclamation = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?User $user = null;*/

    #[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_rep = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le contenu est obligatoire.")]
    #[Assert\Length(max: 255, maxMessage: "Le contenu ne doit pas dépasser {{ limit }} caractères.")]
    private ?string $contenu = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?Reclamation $reclamation = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRep(): ?\DateTimeInterface
    {
        return $this->date_rep;
    }

    public function setDateRep(\DateTimeInterface $date_rep): static
    {
        $this->date_rep = $date_rep;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): static
    {
        $this->reclamation = $reclamation;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
