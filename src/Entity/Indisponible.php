<?php

namespace App\Entity;

use App\Repository\IndisponibleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Instructor;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IndisponibleRepository::class)]
class Indisponible
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'instructor')]
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?Instructor $instructor = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?\DateTime $start_date = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?\DateTime $end_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le motif ne doit pas excéder {{ limit }} caractères.',
    )]
    private ?string $motif = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getInstructor(): ?Instructor
    {
        return $this->instructor;
    }

    public function setInstructor(Instructor $instructor): static
    {
        $this->instructor = $instructor;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTime $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTime $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }
}
