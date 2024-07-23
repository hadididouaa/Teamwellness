<?php

namespace App\Entity;

use App\Repository\ReponsesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponsesRepository::class)]
class Reponses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $scorereponse = null;
    

    #[ORM\ManyToOne(inversedBy: 'Reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Collaborateur $collaborateur = null;

    #[ORM\ManyToOne(inversedBy: 'Reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToOne(inversedBy: 'Reponses')]
   
    private ?Evaluation $evaluation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScorereponse(): ?int
    {
        return $this->scorereponse;
    }

    public function setScorereponse(int $scorereponse): static
    {
        $this->scorereponse = $scorereponse;

        return $this;
    }

    public function getCollaborateur(): ?Collaborateur
    {
        return $this->collaborateur;
    }

    public function setCollaborateur(?Collaborateur $collaborateur): static
    {
        $this->collaborateur = $collaborateur;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getEvaluation(): ?Evaluation
    {
        return $this->evaluation;
    }

    public function setEvaluation(?Evaluation $evaluation): static
    {
        $this->evaluation = $evaluation;

        return $this;
    }
}
