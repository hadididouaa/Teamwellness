<?php

namespace App\Entity;

use App\Repository\CollaborateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CollaborateurRepository::class)]
#[UniqueEntity("email", message:"Cette adresse email est déjà utilisée.")]
class Collaborateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "L'email ne peut pas être vide.")]
    #[Assert\Type(type: "string", message: "L'email doit être une chaîne de caractères.")]
    #[Assert\Regex(
        pattern: "/^[a-z]+\.[a-z]+@teamwillgroup\.com$/i",
        message: "L'email doit être au format prenom.nom@teamwillgroup.com"
    )]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'collaborateur', targetEntity: Reponses::class)]
    private Collection $Reponses;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Evaluation $Evaluation = null;

    public function __construct()
    {
        $this->Reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Reponses>
     */
    public function getReponses(): Collection
    {
        return $this->Reponses;
    }

    public function addReponse(Reponses $reponse): static
    {
        if (!$this->Reponses->contains($reponse)) {
            $this->Reponses->add($reponse);
            $reponse->setCollaborateur($this);
        }

        return $this;
    }

    public function removeReponse(Reponses $reponse): static
    {
        if ($this->Reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getCollaborateur() === $this) {
                $reponse->setCollaborateur(null);
            }
        }

        return $this;
    }

    public function getEvaluation(): ?Evaluation
    {
        return $this->Evaluation;
    }

    public function setEvaluation(?Evaluation $Evaluation): static
    {
        $this->Evaluation = $Evaluation;

        return $this;
    }
}