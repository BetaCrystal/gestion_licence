<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CoursePeriod;
use App\Entity\SchoolYear;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CoursePeriodFixtures extends Fixture implements DependentFixtureInterface
{
    public static function data(): array
    {
        return [
            [
                'schoolYearRef' => 'school_year_1',
                'startDate' => '2026-09-02',
                'endDate' => '2026-09-11'
            ],
            [
                'schoolYearRef' => 'school_year_1',
                'startDate' => '2026-10-05',
                'endDate' => '2026-10-09'
            ],
            [
                'schoolYearRef' => 'school_year_1',
                'startDate' => '2026-11-02',
                'endDate' => '2026-11-06'
            ],
            [
                'schoolYearRef' => 'school_year_1',
                'startDate' => '2026-11-30',
                'endDate' => '2026-12-04'
            ],
            [
                'schoolYearRef' => 'school_year_1',
                'startDate' => '2027-01-04',
                'endDate' => '2027-01-08'
            ],
            [
                'schoolYearRef' => 'school_year_1',
                'startDate' => '2027-02-01',
                'endDate' => '2027-02-05'
            ],
            [
                'schoolYearRef' => 'school_year_1',
                'startDate' => '2027-03-01',
                'endDate' => '2027-03-05'
            ],
            [
                'schoolYearRef' => 'school_year_1',
                'startDate' => '2027-03-29',
                'endDate' => '2027-04-02'
            ],
            [
                'schoolYearRef' => 'school_year_1',
                'startDate' => '2027-04-26',
                'endDate' => '2027-04-30'
            ],
            [
                'schoolYearRef' => 'school_year_1',
                'startDate' => '2027-05-24',
                'endDate' => '2027-05-28'
            ],
            [
                'schoolYearRef' => 'school_year_2',
                'startDate' => '2027-09-02',
                'endDate' => '2027-09-10'
            ],
            [
                'schoolYearRef' => 'school_year_2',
                'startDate' => '2027-10-04',
                'endDate' => '2027-10-08'
            ],
            [
                'schoolYearRef' => 'school_year_3',
                'startDate' => '2028-09-04',
                'endDate' => '2028-09-08'
            ],
        ];
    }
    public function load(ObjectManager $manager): void
    {
        foreach (self::data() as $index => $data) {

            $coursePeriod = new CoursePeriod();

            $coursePeriod->setStartDate(new \DateTime($data['startDate']));
            $coursePeriod->setEndDate(new \DateTime($data['endDate']));

            $coursePeriod->setSchoolYearId(
                $this->getReference(
                    $data['schoolYearRef'],SchoolYear::class
                )
            );

            $refNumber = $index + 1;
            $this->addReference('course_period_'.$refNumber,$coursePeriod);
            $manager->persist($coursePeriod);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SchoolYearFixtures::class,
        ];
    }
}
