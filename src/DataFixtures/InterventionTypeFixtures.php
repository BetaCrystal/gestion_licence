<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\InterventionType;

class InterventionTypeFixtures extends Fixture
{
    public static function data(): array
    {
        return [
            [
                'name' => 'Autonomie',
                'description' => 'Elèves en autonomie',
                'color' => '#6750A4'
            ],
            [
                'name' => 'Conférence',
                'description' => 'Conférence effectuée par un ou plusieurs intervenants',
                'color' => '#028202'
            ],
            [
                'name' => 'Cours',
                'description' => 'Cours dispensé par un ou plusieurs intervenants',
                'color' => '#0EA5E9'
            ],
            [
                'name' => 'Évaluation',
                'description' => "Evaluation sous forme de POC ou d’écrit",
                'color' => '#FF8000'
            ],
            [
                'name' => 'Soutenance',
                'description' => 'Soutenance de fin de projet',
                'color' => '#8C1D18'
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::data() as $index => $data) {

            $interventionType = new InterventionType();
            $interventionType->setName($data['name']);
            $interventionType->setDescription($data['description']);
            $interventionType->setColor($data['color']);
            $this->addReference('intervention_type_' . ($index+1), $interventionType);
            $manager->persist($interventionType);

        }

        $manager->flush();
    }
}
