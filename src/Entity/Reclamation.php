<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/*#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_rec = null;

    #[ORM\Column(length: 255)]
    private ?string $categorie_rec = null;

    #[ORM\Column(length: 255)]
    private ?string $message_rec = null;

    #[ORM\Column(length: 255)]
    private ?string $name_rec = null;

    #[ORM\Column(length: 255)]
    private ?string $email_rec = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?User $user = null;*/
    #[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de réclamation est obligatoire.")]
    private ?\DateTimeInterface $date_rec = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La catégorie est obligatoire.")]
    #[Assert\Length(max: 255, maxMessage: "La catégorie ne doit pas dépasser {{ limit }} caractères.")]
    private ?string $categorie_rec = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le message est obligatoire.")]
    #[Assert\Length(max: 255, maxMessage: "Le message ne doit pas dépasser {{ limit }} caractères.")]
    private ?string $message_rec = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(max: 255, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères.")]
    private ?string $name_rec = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    private ?string $email_rec = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'état est obligatoire.")]
    #[Assert\Length(max: 255, maxMessage: "L'état ne doit pas dépasser {{ limit }} caractères.")]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?User $user = null;

    /**
     * @var Collection<int, Reponse>
     */
    #[ORM\OneToMany(targetEntity: Reponse::class, mappedBy: 'reclamation')]
    private Collection $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRec(): ?\DateTimeInterface
    {
        return $this->date_rec;
    }

    public function setDateRec(\DateTimeInterface $date_rec): static
    {
        $this->date_rec = $date_rec;

        return $this;
    }

    public function getCategorieRec(): ?string
    {
        return $this->categorie_rec;
    }

    public function setCategorieRec(string $categorie_rec): static
    {
        $this->categorie_rec = $categorie_rec;

        return $this;
    }

    public function getMessageRec(): ?string
    {
        return $this->message_rec;
    }

    public function setMessageRec(string $message_rec): static
    {
        $this->message_rec = $message_rec;

        return $this;
    }

    public function getNameRec(): ?string
    {
        return $this->name_rec;
    }

    public function setNameRec(string $name_rec): static
    {
        $this->name_rec = $name_rec;

        return $this;
    }

    public function getEmailRec(): ?string
    {
        return $this->email_rec;
    }

    public function setEmailRec(string $email_rec): static
    {
        $this->email_rec = $email_rec;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

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

    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): static
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setReclamation($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): static
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getReclamation() === $this) {
                $reponse->setReclamation(null);
            }
        }

        return $this;
    }
}
