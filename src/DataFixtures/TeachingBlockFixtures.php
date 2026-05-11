<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\TeachingBlock;

class TeachingBlockFixtures extends Fixture
{
    public static function data(): array
    {
        return [
            [
                'code' => 'B1',
                'name' => 'Piloter',
                'description' => 'Piloter un projet informatique',
                'hoursCount' => 87.5
            ],
            [
                'code' => 'B2',
                'name' => 'Coordoner',
                'description' => 'Coordonner une équipe projet',
                'hoursCount' => 105
            ],
            [
                'code' => 'B3',
                'name' => 'Superviser',
                'description' => "Superviser la mise en oeuvre d’un projet informatique",
                'hoursCount' => 14
            ],
            [
                'code' => 'B4',
                'name' => 'Coordoner',
                'description' => 'Coordoner le cycle de vie des applications',
                'hoursCount' => 297.5
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::data() as $index => $data) {
            $teachingBlock = new TeachingBlock();
            $teachingBlock->setCode($data['code']);
            $teachingBlock->setName($data['name']);
            $teachingBlock->setDescription($data['description']);
            $teachingBlock->setHoursCount($data['hoursCount']);
            $a = $index + 1;
            $this->addReference('teachingBlock_'.$a, $teachingBlock);
            $manager->persist($teachingBlock);

        }

        $manager->flush();
    }
}
