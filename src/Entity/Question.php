<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $num = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $enonce = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $rep1 = null;

    #[ORM\Column]
    private ?int $score1 = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $rep2 = null;

    #[ORM\Column]
    private ?int $score2 = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $rep3 = null;

    #[ORM\Column]
    private ?int $score3 = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Reponses::class)]
    private Collection $Reponses;

    public function __construct()
    {
        $this->Reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): static
    {
        $this->num = $num;

        return $this;
    }

    public function getEnonce(): ?string
    {
        return $this->enonce;
    }

    public function setEnonce(string $enonce): static
    {
        $this->enonce = $enonce;

        return $this;
    }

    public function getRep1(): ?string
    {
        return $this->rep1;
    }

    public function setRep1(string $rep1): static
    {
        $this->rep1 = $rep1;

        return $this;
    }

    public function getScore1(): ?int
    {
        return $this->score1;
    }

    public function setScore1(int $score1): static
    {
        $this->score1 = $score1;

        return $this;
    }

    public function getRep2(): ?string
    {
        return $this->rep2;
    }

    public function setRep2(string $rep2): static
    {
        $this->rep2 = $rep2;

        return $this;
    }

    public function getScore2(): ?int
    {
        return $this->score2;
    }

    public function setScore2(int $score2): static
    {
        $this->score2 = $score2;

        return $this;
    }

    public function getRep3(): ?string
    {
        return $this->rep3;
    }

    public function setRep3(string $rep3): static
    {
        $this->rep3 = $rep3;

        return $this;
    }

    public function getScore3(): ?int
    {
        return $this->score3;
    }

    public function setScore3(int $score3): static
    {
        $this->score3 = $score3;

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
            $reponse->setQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponses $reponse): static
    {
        if ($this->Reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getQuestion() === $this) {
                $reponse->setQuestion(null);
            }
        }

        return $this;
    }
}
