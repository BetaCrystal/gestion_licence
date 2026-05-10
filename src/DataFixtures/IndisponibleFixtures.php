<?php

namespace App\DataFixtures;

use App\Entity\Indisponible;
use App\Entity\Instructor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class IndisponibleFixtures extends Fixture implements DependentFixtureInterface{

    public static function data(): array
    {
        return [];

    }

    public function load(ObjectManager $manager): void
    {
    foreach (self::data() as $index => $data) {

            $indisponible = new Indisponible();

            $indisponible->setStartDate(new \DateTime($data['start_date']));
            $indisponible->setEndDate(new \DateTime($data['end_date']));
            $indisponible->setMotif($data['motif']);

            $indisponible->setInstructor(
                $this->getReference(
                    $data['instructor'],Instructor::class
                )
            );

            $refNumber = $index + 1;
            $this->addReference('indisponible_'.$refNumber,$indisponible);
            $manager->persist($indisponible);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [InstructorFixtures::class];
    }
}
