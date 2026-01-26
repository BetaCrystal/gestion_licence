<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Module;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Instructor;
use App\Entity\ModuleInstructor;

class ModuleInstructorFixtures extends Fixture implements DependentFixtureInterface
{
    public static function data(): array
    {
        return[
            ['module_id' => '87', 'instructor_id' => '17'],
            ['module_id' => '88', 'instructor_id' => '17'],
            ['module_id' => '90', 'instructor_id' => '17'],
            ['module_id' => '96', 'instructor_id' => '17'],
            ['module_id' => '87', 'instructor_id' => '18'],
            ['module_id' => '91', 'instructor_id' => '18'],
            ['module_id' => '92', 'instructor_id' => '18'],
            ['module_id' => '94', 'instructor_id' => '18'],
            ['module_id' => '99', 'instructor_id' => '18']
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::data() as $key => $data) {
            $moduleInstructor = new ModuleInstructor();
            $moduleInstructor->setModule(
                $this->getReference('Module_' . ($data['module_id'] - 86), Module::class)
            );
            $moduleInstructor->setInstructor(
                $this->getReference('instructor_' . ($data['instructor_id'] - 16), Instructor::class)
            );
            $manager->persist($moduleInstructor);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ModuleFixtures::class,
            InstructorFixtures::class
        ];
    }
}
