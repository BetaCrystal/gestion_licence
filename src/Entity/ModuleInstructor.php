<?php

namespace App\Entity;

use App\Repository\ModuleInstructorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleInstructorRepository::class)]
#[ORM\Table(name: 'module_instructor')]
#[ORM\UniqueConstraint(name: 'module_instructor_unique', columns: ['module_id', 'instructor_id'])]
class ModuleInstructor
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Module::class, inversedBy: 'moduleInstructors')]
    #[ORM\JoinColumn(name: 'module_id', referencedColumnName: 'id', nullable: false)]
    private ?Module $module = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Instructor::class, inversedBy: 'moduleInstructors')]
    #[ORM\JoinColumn(name: 'instructor_id', referencedColumnName: 'id', nullable: false)]
    private ?Instructor $instructor = null;

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): static
    {
        $this->module = $module;

        return $this;
    }

    public function getInstructor(): ?Instructor
    {
        return $this->instructor;
    }

    public function setInstructor(?Instructor $instructor): static
    {
        $this->instructor = $instructor;

        return $this;
    }
}
