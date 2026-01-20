<?php
// src/Twig/Components/Sidebar.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Sidebar
{
public function getMenuStructure(): array
{
    return [
        [
            'titre' => 'MENU',
            'liens' => [
                ['label' => 'Calendrier', 'route' => 'app_calendrier', 'icone' => 'calendrier'],
                ['label' => 'Interventions', 'route' => 'app_intervention', 'icone' => 'intervention'],
                ['label' => 'Corps enseignant', 'route' => 'app_enseignant', 'icone' => 'enseignants'],
            ],
        ],
        [
            'titre' => 'PARAMÉTRAGE',
            'liens' => [
                ['label' => 'Modules', 'route' => 'app_modules', 'icone' => 'module'],
                ['label' => "Blocs d'enseignement", 'route' => 'app_blocks', 'icone' => 'blocs'],
                ['label' => 'Années scolaire', 'route' => 'app_année', 'icone' => 'années'],
                ['label' => "Types d'intervention", 'route' => 'app_intervention', 'icone' => 'intervention'],
            ],
        ],
    ];
}
}
