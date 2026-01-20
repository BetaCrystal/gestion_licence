<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Module;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\TeachingBlock;

class ModuleFixtures extends Fixture implements DependentFixtureInterface
{
    public static function data(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | B1 – Piloter un projet informatique
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'GESTION_PROJET_AGILE',
                'parent' => '',
                'name' => 'Gestion de projet – Méthodes Agile',
                'description' => 'Apprendre à gérer un projet avec des méthodes agiles',
                'hoursCount' => 16,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_1',
            ],
            [
                'code' => 'CADRE_LEGAL',
                'parent' => '',
                'name' => 'Cadre légal – Droit numérique',
                'description' => 'Connaître les règles du numérique',
                'hoursCount' => 12 ,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_1',
            ],
            [
                'code' => 'RGPD',
                'parent' => 'CADRE_LEGAL',
                'name' => 'RGPD',
                'description' => 'Respecter le RGPD',
                'hoursCount' => 12,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_1',
            ],
            [
                'code' => 'PROPRIETE_INTELLECTUELLE',
                'parent' => 'CADRE_LEGAL',
                'name' => 'Propriété intellectuelle',
                'description' => 'Comprendre la propriété intellectuelle',
                'hoursCount' => 14,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_1',
            ],
            [
                'code' => 'RSE',
                'parent' => 'CADRE_LEGAL',
                'name' => 'RSE',
                'description' => 'Responsabilité sociétale des entreprises',
                'hoursCount' => 128,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_1',
            ],
            [
                'code' => 'ACCESSIBILITE',
                'parent' => 'CADRE_LEGAL',
                'name' => 'Accessibilité',
                'description' => 'Rendre les applications accessibles',
                'hoursCount' => 23,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_1',
            ],
            [
                'code' => 'ECO_CONCEPTION',
                'parent' => '',
                'name' => 'Éco-conception',
                'description' => 'Développer de manière éco-responsable',
                'hoursCount' => 8.5,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_1',
            ],

            /*
            |--------------------------------------------------------------------------
            | B2 – Coordonner une équipe projet
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'ANGLAIS_TOEIC',
                'parent' => '',
                'name' => 'Anglais – Préparation au TOEIC',
                'description' => 'Préparer la certification TOEIC',
                'hoursCount' => 70,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_2',
            ],
            [
                'code' => 'COMMUNICATION_SOFT_SKILLS',
                'parent' => '',
                'name' => 'Communication – Soft skills',
                'description' => 'Développer les compétences relationnelles',
                'hoursCount' => 28,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_2',
            ],
            [
                'code' => 'DEVOPS_CYBER',
                'parent' => '',
                'name' => 'DevOps et cybersécurité',
                'description' => 'Sécuriser et industrialiser les déploiements',
                'hoursCount' => 34,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_2',
            ],
            [
                'code' => 'DOCKER',
                'parent' => 'DEVOPS_CYBER',
                'name' => 'Docker',
                'description' => 'Conteneurisation des applications',
                'hoursCount' => 14,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_2',
            ],
            [
                'code' => 'CI',
                'parent' => 'DEVOPS_CYBER',
                'name' => 'CI',
                'description' => 'Intégration continue',
                'hoursCount' => 7,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_2',
            ],
            [
                'code' => 'DEPLOY_SECURISE',
                'parent' => 'DEVOPS_CYBER',
                'name' => 'Deploy sécurisé',
                'description' => 'Déployer en toute sécurité',
                'hoursCount' => 21,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_2',
            ],

            /*
            |--------------------------------------------------------------------------
            | B3 – Superviser la mise en œuvre
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'COMPTE_RENDU',
                'parent' => '',
                'name' => 'Rédaction de comptes rendus',
                'description' => 'Rédiger des comptes rendus d’activités',
                'hoursCount' => 14,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_3',
            ],

            /*
            |--------------------------------------------------------------------------
            | B4 – Cycle de vie des applications
            |--------------------------------------------------------------------------
            */
            [
                'code' => 'UX_UI',
                'parent' => '',
                'name' => 'UX / UI et mode projet',
                'description' => 'Concevoir des interfaces utilisateurs',
                'hoursCount' => 28,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_4',
            ],
            [
                'code' => 'DEV_FRONT',
                'parent' => '',
                'name' => 'Développement front-end',
                'description' => 'Développer des interfaces web',
                'hoursCount' => 102,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_4',
            ],
            [
                'code' => 'DEV_BACK',
                'parent' => '',
                'name' => 'Développement back-end',
                'description' => 'Développer la partie serveur',
                'hoursCount' => 170,
                'capstone_project' => false,
                'teachingBlock' => 'teachingBlock_4',
            ],
        ];
    }   


    public function load(ObjectManager $manager): void
    {
        foreach (self::data() as $index => $data) {

            $module = new Module();

            $module->setCode($data['code']);
            $module->setName($data['name']);
            $module->setDescription($data['description']);


            $hours = $data ['hoursCount']; 
            $module->setHoursCount($hours);

            $module->setCapstoneProject($data['capstone_project']);

            if (!empty($data['parent'])) {
                $parent = $this->getReference($data['parent'] , Module::class);
                $module->setParent($parent);
            } else {
                $module->setParent(null);
            }

            $teachingBlock = $this->getReference($data['teachingBlock'], TeachingBlock::class);
            $module->setTeachingBlock($teachingBlock);

            $manager->persist($module);

            $this->addReference($data['code'], $module);
                        
            $a = $index + 1;
            $this->addReference('Module_'.$a, $module);
            
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            TeachingBlockFixtures::class,
        ];
    }
}
