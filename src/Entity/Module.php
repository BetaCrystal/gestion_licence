<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Course;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;
    
    #[ORM\Column]
    private ?int $hours_count = null;

    #[ORM\Column]
    private ?bool $capstone_project = null;

    /**
     * @var Collection<int, Instructor>
     */
    #[ORM\ManyToMany(targetEntity: Instructor::class, inversedBy: 'modules')]
    private Collection $instructors;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $children;

    /**
     * @var Collection<int, Course>
     */
    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'module')]
    private Collection $courses;

    #[ORM\ManyToOne(targetEntity: TeachingBlock::class, inversedBy: 'modules')]
    #[ORM\JoinColumn(name: 'teaching_block_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?TeachingBlock $teachingBlock = null;


    public function __construct()
    {
        $this->instructors = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

    // ------------------- GETTERS & SETTERS -------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getHoursCount(): ?int
    {
        return $this->hours_count;
    }

    public function setHoursCount(int $hours_count): self
    {
        $this->hours_count = $hours_count;
        return $this;
    }

    public function isCapstoneProject(): ?bool
    {
        return $this->capstone_project;
    }

    public function setCapstoneProject(bool $capstone_project): self
    {
        $this->capstone_project = $capstone_project;
        return $this;
    }

    // ----------- Relation ManyToMany Instructors ----------
    public function getInstructors(): Collection
    {
        return $this->instructors;
    }

    public function addInstructor(Instructor $instructor): self
    {
        if (!$this->instructors->contains($instructor)) {
            $this->instructors->add($instructor);
        }
        return $this;
    }

    public function removeInstructor(Instructor $instructor): self
    {
        $this->instructors->removeElement($instructor);
        return $this;
    }

    // ----------- Self-relation Parent / Children ----------
    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }
        return $this;
    }

    // ----------- Relation TeachingBlock ----------
    public function getTeachingBlock(): ?TeachingBlock
    {
        return $this->teachingBlock;
    }

    public function setTeachingBlock(?TeachingBlock $teachingBlock): self
    {
        $this->teachingBlock = $teachingBlock;
        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setModule($this);
        }
        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            if ($course->getModule() === $this) {
                $course->setModule(null);
            }
        }
        return $this;
    }
}
