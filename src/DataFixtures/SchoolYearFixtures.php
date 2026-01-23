<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\SchoolYear;

class SchoolYearFixtures extends Fixture
{
    public static function data(): array
    {
        return [
            [
                'name' => '2026',
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31'
            ],
            [
                'name' => '2027',
                'start_date' => '2027-01-01',
                'end_date' => '2027-12-31'
            ],
            [
                'name' => '2028',
                'start_date' => '2028-01-01',
                'end_date' => '2028-12-31'
            ],
        ];
    }
    public function load(ObjectManager $manager): void
    {
        $i = 0;

        foreach (self::data() as $data) {

            $schoolYear = new SchoolYear();
            $schoolYear->setName($data['name']);
            $schoolYear->setStartDate(new \DateTime($data['start_date']));
            $schoolYear->setEndDate(new \DateTime($data['end_date']));
            $i = $i + 1;
            $this->addReference('school_year_'.$i , $schoolYear);
            $manager->persist($schoolYear);
        }

        $manager->flush();
    }
}
