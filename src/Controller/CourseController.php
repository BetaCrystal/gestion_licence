<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CourseForm;
use App\Entity\CoursePeriod;
use App\Entity\SchoolYear;

class CourseController extends AbstractController
{

    #[Route(path: '/twig/course', name: 'app_course', methods: ['GET'])]
    public function index(Course $course): Response
    {
        $course = $course->getCourse($course);
        return $this->render('intervention/index.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route(path: '/twig/add_course', name: 'app_add_course', methods: ['GET','POST'])]
    public function addCourse(Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = new Course();

        // Pre-fill dates if date parameter is provided
        $dateStr = $request->query->get('date');
        if ($dateStr) {
            $selectedDate = new \DateTime($dateStr);
            $course->setStartDate($selectedDate);
            $course->setEndDate($selectedDate);
        }

        $form = $this->createForm(CourseForm::class, $course);
        $form->handleRequest($request);

        $submitted = $form->isSubmitted();
        $this->addFlash('info', 'Form submitted: ' . ($submitted ? 'yes' : 'no'));
        if ($submitted) {
            $valid = $form->isValid();
            $this->addFlash('info', 'Form valid: ' . ($valid ? 'yes' : 'no'));
            if (!$valid) {
                $errors = [];
                foreach ($form->getErrors(true) as $error) {
                    $errors[] = $error->getMessage();
                }
                $this->addFlash('error', 'Form errors: ' . implode(', ', $errors));
            }
        } else {
            $this->addFlash('info', 'Form valid: N/A (not submitted)');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $course->getStartDate();

            // Find school year containing the start date
            $qb = $entityManager->createQueryBuilder();
            $schoolYear = $qb->select('sy')
                ->from(SchoolYear::class, 'sy')
                ->where('sy.start_date <= :date AND sy.end_date >= :date')
                ->setParameter('date', $startDate)
                ->getQuery()
                ->getOneOrNullResult();

            if (!$schoolYear) {
                // Handle error, perhaps throw exception or add flash message
                $this->addFlash('error', 'Aucune année scolaire trouvée à cette date.');
                return $this->redirectToRoute('app_add_course');
            }

            // Calculate week start (Monday) and end (Sunday)
            $weekStart = clone $startDate;
            $weekStart->modify('monday this week');
            $weekEnd = clone $weekStart;
            $weekEnd->modify('+6 days');

            // Find or create CoursePeriod
            // Crée le courseperiod s'il n'existe pas. Obligatoire pour ajouter une course car ça doit être automatique
            // (pas dans le formulaire) et la course doit y être rattachée.
            $coursePeriod = $entityManager->getRepository(CoursePeriod::class)->findOneBy([
                'school_year_id' => $schoolYear,
                'start_date' => $weekStart,
                'end_date' => $weekEnd
            ]);

            if (!$coursePeriod) {
                $coursePeriod = new CoursePeriod();
                $coursePeriod->setSchoolYearId($schoolYear);
                $coursePeriod->setStartDate($weekStart);
                $coursePeriod->setEndDate($weekEnd);
                $entityManager->persist($coursePeriod);
            }

            $course->setCoursePeriod($coursePeriod);
            $this->addFlash('success', 'Client ajouté avec succès !');

            $entityManager->persist($course);
            $entityManager->flush();

            return $this->redirectToRoute('app_calendar_calendar');
        }

        return $this->render('admin/courses/add_course.html.twig', [
            'form' => $form,
        ]);
    }
}