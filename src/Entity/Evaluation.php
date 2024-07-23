<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $moyenne = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $interpretation = null;

    #[ORM\OneToMany(mappedBy: 'evaluation', targetEntity: Reponses::class)]
    private Collection $Reponses;
    

    public function __construct()
    {
        $this->Reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    

    public function getMoyenne(): ?int
    {
        return $this->moyenne;
    }

    public function setMoyenne(int $moyenne): static
    {
        $this->moyenne = $moyenne;

        return $this;
    }

    public function getInterpretation(): ?string
    {
        return $this->interpretation;
    }

    public function setInterpretation(string $interpretation): static
    {
        $this->interpretation = $interpretation;

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
            $reponse->setEvaluation($this);
        }

        return $this;
    }

    public function removeReponse(Reponses $reponse): static
    {
        if ($this->Reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getEvaluation() === $this) {
                $reponse->setEvaluation(null);
            }
        }

        return $this;
    }
}
