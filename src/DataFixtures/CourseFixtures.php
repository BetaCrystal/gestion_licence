<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Course;
use App\Entity\CoursePeriod;
use App\Entity\InterventionType;
use App\Entity\Instructor;
use App\Entity\Module;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CourseFixtures extends Fixture implements DependentFixtureInterface
{
    public static function data(): array
    {
        return [
            [
                'startDate' => '2026-09-04 13:00:00',
                'endDate' => '2026-09-04 17:00:00',
                'coursePeriodRef' => 'course_period_1',
                'interventionTypeRef' => 'intervention_type_2',
                'moduleRef' => 'GESTION_PROJET_AGILE', // Gestion de projet – Méthodes Agile
                'instructors' => ['instructor_1'],
                'remotely' => TRUE,
                'module' => 'Module_1',
                'title' => 'Méthode agile'
            ],
            [
                'startDate' => '2026-10-06 08:30:00',
                'endDate' => '2026-10-06 12:30:00',
                'coursePeriodRef' => 'course_period_2',
                'interventionTypeRef' => 'intervention_type_3',
                'moduleRef' => 'CADRE_LEGAL', // Cadre légal – Droit numérique
                'instructors' => ['instructor_3'],
                'remotely' => FALSE,
                'module' => 'Module_7',
                'title' => 'Éco-conception'
            ],
            [
                'startDate' => '2026-11-02 13:30:00',
                'endDate' => '2026-11-02 17:30:00',
                'coursePeriodRef' => 'course_period_1',
                'interventionTypeRef' => 'intervention_type_2',
                'moduleRef' => 'DEVOPS_CYBER', // DevOps et cybersécurité
                'instructors' => ['instructor_3','instructor_1'],
                'remotely' => FALSE,
                'module' => 'Module_10',
                'title' => 'Devops/Cyber'
            ],
            [
                'startDate' => '2026-11-30 08:30:00',
                'endDate' => '2026-11-30 12:30:00',
                'coursePeriodRef' => 'course_period_1',
                'interventionTypeRef' => 'intervention_type_1',
                'moduleRef' => 'DEV_FRONT', // Développement front-end
                'instructors' => ['instructor_1'],
                'remotely' => FALSE,
                'module' => 'Module_16',
                'title' => 'Javascript'
            ],
            [
                'startDate' => '2026-09-04 13:00:00',
                'endDate' => '2026-09-04 17:00:00',
                'coursePeriodRef' => 'course_period_1',
                'interventionTypeRef' => 'intervention_type_2',
                'instructors' => ['instructor_2'],
                'remotely' => TRUE,
                'module' => 'Module_1',
                'title' => 'Méthode agile'
            ],
            [
                'startDate' => '2026-10-06 08:30:00',
                'endDate' => '2026-10-06 12:30:00',
                'coursePeriodRef' => 'course_period_2',
                'interventionTypeRef' => 'intervention_type_3',
                'instructors' => ['instructor_3'],
                'remotely' => FALSE,
                'module' => 'Module_7',
                'title' => 'Éco-conception'
            ],
            [
                'startDate' => '2026-11-02 13:30:00',
                'endDate' => '2026-11-02 17:30:00',
                'coursePeriodRef' => 'course_period_1',
                'interventionTypeRef' => 'intervention_type_2',
                'instructors' => ['instructor_3','instructor_2'],
                'remotely' => FALSE,
                'module' => 'Module_10',
                'title' => 'Devops/Cyber'
            ],
            [
                'startDate' => '2026-11-30 08:30:00',
                'endDate' => '2026-11-30 12:30:00',
                'coursePeriodRef' => 'course_period_1',
                'interventionTypeRef' => 'intervention_type_1',
                'instructors' => ['instructor_2'],
                'remotely' => FALSE,
                'module' => 'Module_16',
                'title' => 'Javascript'
            ],
            [
                'startDate' => '2026-09-04 13:00:00',
                'endDate' => '2026-09-04 17:00:00',
                'coursePeriodRef' => 'course_period_1',
                'interventionTypeRef' => 'intervention_type_2',
                'instructors' => ['instructor_2'],
                'remotely' => TRUE,
                'module' => 'Module_1',
                'title' => 'Méthode agile'
            ],
            [
                'startDate' => '2026-10-06 08:30:00',
                'endDate' => '2026-10-06 12:30:00',
                'coursePeriodRef' => 'course_period_2',
                'interventionTypeRef' => 'intervention_type_3',
                'instructors' => ['instructor_3'],
                'remotely' => FALSE,
                'module' => 'Module_7',
                'title' => 'Éco-conception'
            ],
            [
                'startDate' => '2026-11-02 13:30:00',
                'endDate' => '2026-11-02 17:30:00',
                'coursePeriodRef' => 'course_period_1',
                'interventionTypeRef' => 'intervention_type_2',
                'instructors' => ['instructor_3','instructor_2'],
                'remotely' => FALSE,
                'module' => 'Module_10',
                'title' => 'Devops/Cyber'
            ],
            [
                'startDate' => '2026-11-30 08:30:00',
                'endDate' => '2026-11-30 12:30:00',
                'coursePeriodRef' => 'course_period_1',
                'interventionTypeRef' => 'intervention_type_1',
                'instructors' => ['instructor_2'],
                'remotely' => FALSE,
                'module' => 'Module_16',
                'title' => 'Javascript'
            ],
        ];
    }

    public function load(ObjectManager $manager): void
        {
            foreach (self::data() as $index => $data) {

                $course = new Course();

                // Dates
                $course->setStartDate(new \DateTime($data['startDate']));
                $course->setEndDate(new \DateTime($data['endDate']));

                // Champs simples
                $course->setRemotely($data['remotely']);
                $course->setTitle($data['title']);

                // Relations ManyToOne
                $course->setCoursePeriod(
                    $this->getReference($data['coursePeriodRef'],CoursePeriod::class)
                );

                $course->setInterventionType(
                    $this->getReference($data['interventionTypeRef'], InterventionType::class)
                );

                $course->setModule(
                    $this->getReference($data['module'], Module::class)
                );

                // Relation ManyToMany (Instructor)
                foreach ($data['instructors'] as $instructorRef) {
                    $course->addCourseInstructor(
                        $this->getReference($instructorRef, Instructor::class)
                    );
                }

                $manager->persist($course);
            }

            $manager->flush();
        }

    public function getDependencies(): array
    {
        return [
            CoursePeriodFixtures::class,
            InterventionTypeFixtures::class,
            InstructorFixtures::class,
            ModuleFixtures::class,
        ];
    }
}
