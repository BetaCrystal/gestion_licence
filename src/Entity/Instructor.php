<?php

namespace App\Entity;

use App\Repository\InstructorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity(repositoryClass: InstructorRepository::class)]
class Instructor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    /**
     * @var Collection<int, ModuleInstructor>
     */
    #[ORM\OneToMany(targetEntity: ModuleInstructor::class, mappedBy: 'instructor')]
    private Collection $moduleInstructors;

    public function __construct()
    {
        $this->moduleInstructors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, ModuleInstructor>
     */
    public function getModuleInstructors(): Collection
    {
        return $this->moduleInstructors;
    }

    public function addModuleInstructor(ModuleInstructor $moduleInstructor): static
    {
        if (!$this->moduleInstructors->contains($moduleInstructor)) {
            $this->moduleInstructors->add($moduleInstructor);
            $moduleInstructor->setInstructor($this);
        }

        return $this;
    }

    public function removeModuleInstructor(ModuleInstructor $moduleInstructor): static
    {
        $this->moduleInstructors->removeElement($moduleInstructor);

        return $this;
    }
}
