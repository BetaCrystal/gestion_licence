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
    private ?User $user = null;

    /**
     * @var Collection<int, Module>
     */
    #[ORM\ManyToMany(targetEntity: Module::class, inversedBy: 'instructors')]
    private Collection $Module;

    public function __construct()
    {
        $this->Module = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Module>
     */
    public function getModule(): Collection
    {
        return $this->Module;
    }

    public function addModule(Module $module): static
    {
        if (!$this->Module->contains($module)) {
            $this->Module->add($module);
        }

        return $this;
    }

    public function removeModule(Module $module): static
    {
        $this->Module->removeElement($module);

        return $this;
    }
}
