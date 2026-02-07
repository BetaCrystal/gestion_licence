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
use App\Repository\CoursePeriodRepository;

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
    public function addCourse(Request $request, EntityManagerInterface $entityManager, CoursePeriodRepository $coursePeriodRepository): Response
    {
        $course = new Course();
        $coursePeriod = $coursePeriodRepository->findAll();

        // Pre-fill dates if date parameter is provided
        $dateStr = $request->query->get('date');
        if ($dateStr) {
            $selectedDate = new \DateTime($dateStr);
            $course->setStartDate($selectedDate);
            $course->setEndDate($selectedDate);
        }

        foreach ($coursePeriod as $cp) {
            if ($cp->getStartDate() <= $course->getStartDate() && $cp->getEndDate() >= $course->getStartDate()) {
                $course->setCoursePeriod($cp);
            }
        }
        if (!$course->getCoursePeriod()) {
            $this->addFlash('error', 'Aucune période de cours trouvée pour cette date.');
            return $this->redirectToRoute('app_calendar_calendar');
        }

        $form = $this->createForm(CourseForm::class, $course);
        $form->handleRequest($request);


        $submitted = $form->isSubmitted();
        //erreurs de champs vides
        if ($submitted && !$form->isValid()) {
            if (!$form->get('startDate')->getData()) {
                $this->addFlash('error', 'La date de début est obligatoire.');
            }
            if (!$form->get('endDate')->getData()) {
                $this->addFlash('error', 'La date de fin est obligatoire.');
            }
            if (!$form->get('module')->getData()) {
                $this->addFlash('error', 'Le module est obligatoire.');
            }
            if (!$form->get('interventionType')->getData()) {
                $this->addFlash('error', 'Le type d\'intervention est obligatoire.');
            }
            if ($form->get('CourseInstructor')->getData()->isEmpty()) {
                $this->addFlash('error', 'Au moins un intervenant est obligatoire.');
            }
            if ($form->get('remotely')->getData() === null) {
                $this->addFlash('error', 'Le mode (présentiel/à distance) est obligatoire.');
            }
            if ($form->get('startDate')->getData() && $form->get('endDate')->getData()) {
                $startDate = $form->get('startDate')->getData();
                $endDate = $form->get('endDate')->getData();
                if ($startDate > $endDate) {
                    $this->addFlash('error', 'La date de début doit être antérieure à la date de fin.');
                }
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $course->getStartDate();

            // Find school year containing the start date
            $qb = $entityManager->createQueryBuilder();
            $schoolYear = $qb->select('sy')
                ->from(SchoolYear::class, 'sy')
                ->where('sy.startDate <= :date AND sy.endDate >= :date')
                ->orderBy('sy.startDate', 'DESC')
                ->setMaxResults(1)
                ->setParameter('date', $startDate)
                ->getQuery()
                ->getOneOrNullResult();

            if (!$schoolYear) {
                $this->addFlash('error', 'Aucune année scolaire trouvée à cette date.');
                return $this->redirectToRoute('app_calendar_calendar');
            }

            $this->addFlash('success', 'Cours ajouté avec succès !');

            $entityManager->persist($course);
            $entityManager->flush();

            return $this->redirectToRoute('app_calendar_calendar');
        }

        return $this->render('admin/courses/add_course.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/twig/{id}/edit_course/', name: 'app_edit_course', methods: ['GET','POST'])]
    public function changeCourse(Request $request, EntityManagerInterface $entityManager, Course $course): Response
    {
        // Pre-fill dates if date parameter is provided
        $dateStr = $request->query->get('date');
        if ($dateStr) {
            $selectedDate = new \DateTime($dateStr);
            $course->setStartDate($selectedDate);
            $course->setEndDate($selectedDate);
        }

        $form = $this->createForm(CourseForm::class, $course);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $course->getStartDate();


            // Find school year containing the start date
            $qb = $entityManager->createQueryBuilder();
            $schoolYear = $qb->select('sy')
                ->from(SchoolYear::class, 'sy')
                ->where('sy.startDate <= :date AND sy.endDate >= :date')
                ->orderBy('sy.startDate', 'DESC')
                ->setMaxResults(1)
                ->setParameter('date', $startDate)
                ->getQuery()
                ->getOneOrNullResult();


            if (!$schoolYear) {
                $this->addFlash('error', 'Aucune année scolaire trouvée à cette date.');
                return $this->redirectToRoute('app_add_course');
            }
            if ($form->get('startDate')->getData() && $form->get('endDate')->getData()) {
                $startDate = $form->get('startDate')->getData();
                $endDate = $form->get('endDate')->getData();
                if ($startDate > $endDate) {
                    $this->addFlash('error', 'La date de début doit être antérieure à la date de fin.');
                }
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
                'schoolYear' => $schoolYear,
                'startDate' => $weekStart,
                'endDate' => $weekEnd
            ]);


            if (!$coursePeriod) {
                $coursePeriod = new CoursePeriod();
                $coursePeriod->setSchoolYear($schoolYear);
                $coursePeriod->setStartDate($weekStart);
                $coursePeriod->setEndDate($weekEnd);
                $entityManager->persist($coursePeriod);
            }


            $course->setCoursePeriod($coursePeriod);
            $entityManager->persist($course);
            $entityManager->flush();


            $this->addFlash('success', 'Intervention modifiée avec succès !');
            return $this->redirectToRoute('app_calendar_calendar');
        }


        return $this->render('admin/courses/change_course.html.twig', [
            'form' => $form->createView(),
            'course' => $course,
        ]);
    }
    #[Route('/course/{id}', name: 'app_course_delete', methods: ['POST'])]
    public function delete(Request $request, Course $course, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_course', $request->request->get('_token'))) {
            $entityManager->remove($course);
            $entityManager->flush();
            $this->addFlash('success', 'Intervention supprimée !');
        }


        return $this->redirectToRoute('liste_interventions');
    }

}
