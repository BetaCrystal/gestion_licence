<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $startDate = null;

    #[ORM\Column]
    private ?\DateTime $endDate = null;


    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CoursePeriod $coursePeriod = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?InterventionType $interventionType = null;

    #[ORM\Column]
    private ?bool $remotely = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Module $module = null;

    /**
     * @var Collection<int, Instructor>
     */
    #[ORM\ManyToMany(targetEntity: Instructor::class)]
    private Collection $CourseInstructor;

    public function __construct()
    {
        $this->CourseInstructor = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCoursePeriod(): ?CoursePeriod
    {
        return $this->coursePeriod;
    }

    public function setCoursePeriod(?CoursePeriod $coursePeriod): static
    {
        $this->coursePeriod = $coursePeriod;

        return $this;
    }

    public function getInterventionType(): ?InterventionType
    {
        return $this->interventionType;
    }

    public function setInterventionType(?InterventionType $interventionType): static
    {
        $this->interventionType = $interventionType;

        return $this;
    }

    public function isRemotely(): ?bool
    {
        return $this->remotely;
    }

    public function setRemotely(bool $remotely): static
    {
        $this->remotely = $remotely;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): static
    {
        $this->module = $module;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this;
    }

    /**
     * @return Collection<int, Instructor>
     */
    public function getCourseInstructor(): Collection
    {
        return $this->CourseInstructor;
    }

    public function addCourseInstructor(Instructor $courseInstructor): static
    {
        if (!$this->CourseInstructor->contains($courseInstructor)) {
            $this->CourseInstructor->add($courseInstructor);
        }

        return $this;
    }

    public function removeCourseInstructor(Instructor $courseInstructor): static
    {
        $this->CourseInstructor->removeElement($courseInstructor);

        return $this;
    }
}
