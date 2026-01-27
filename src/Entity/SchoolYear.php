<?php

namespace App\Entity;

use App\Repository\SchoolYearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SchoolYearRepository::class)]
class SchoolYear
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $endDate = null;

    /**
     * @var Collection<int, CoursePeriod>
     */
    #[ORM\OneToMany(targetEntity: CoursePeriod::class, mappedBy: 'schoolYear')]
    private Collection $coursePeriods;

    public function __construct()
    {
        $this->coursePeriods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection<int, CoursePeriod>
     */
    public function getCoursePeriods(): Collection
    {
        return $this->coursePeriods;
    }

    public function addCoursePeriod(CoursePeriod $coursePeriod): static
    {
        if (!$this->coursePeriods->contains($coursePeriod)) {
            $this->coursePeriods->add($coursePeriod);
            $coursePeriod->setSchoolYearId($this);
        }

        return $this;
    }

    public function removeCoursePeriod(CoursePeriod $coursePeriod): static
    {
        if ($this->coursePeriods->removeElement($coursePeriod)) {
            // set the owning side to null (unless already changed)
            if ($coursePeriod->getSchoolYearId() === $this) {
                $coursePeriod->setSchoolYearId(null);
            }
        }

        return $this;
    }
}
