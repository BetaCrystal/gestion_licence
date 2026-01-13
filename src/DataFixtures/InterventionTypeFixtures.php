<?php
namespace App\DataFixtures;

use App\Entity\InterventionType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

final class InterventionTypeFixtures extends Fixture
{
    public const INTERVENTION_TYPES =
        [
            'intervention_type_1' => [
                'name' => 'Cours',
                'description' => 'Cours basique',
                'color' => '#FF5733'
            ],
            'intervention_type_2' => [
                'name' => 'TP',
                'description' => 'TP sur ordi',
                'color' => '#33FF57'
            ],
            'intervention_type_3' => [
                'name' => 'Projet',
                'description' => 'Projet en équipe',
                'color' => '#3357FF'
            ],
            'intervention_type_4' => [
                'name' => 'Réunion',
                'description' => 'Réunion pour faire le point',
                'color' => '#F1C40F'
            ],
            'intervention_type_5' => [
                'name' => 'Soutenance',
                'description' => '',
                'color' => '#8E44AD'
            ],
            'intervention_type_6' => [
                'name' => 'Examen',
                'description' => 'Examen sur feuille',
                'color' => '#E74C3C'
            ],
            'intervention_type_7' => [
                'name' => 'Autonomie',
                'description' => 'Code en autonomie',
                'color' => 'red'
            ],
            'intervention_type_' => [
                'name' => 'Conférence',
                'description' => 'Conférence avec un intervenant',
                'color' => 'blue'
            ]
        ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::INTERVENTION_TYPES as $reference => $typeData) {
            $type = new InterventionType();
            $type->setName($typeData['name']);
            $type->setDescription($typeData['description']);
            $type->setColor($typeData['color']);
            $manager->persist($type);
        }

        $manager->flush();
    }
}