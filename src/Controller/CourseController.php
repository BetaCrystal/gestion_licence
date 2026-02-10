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
use App\Entity\Instructor;

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
            /*if (!$form->get('startDate')->getData()) {
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
            }*/
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
    public function changeCourse(Request $request, EntityManagerInterface $entityManager, Course $course, CoursePeriodRepository $coursePeriodRepository): Response
    {
        $coursePeriod = $coursePeriodRepository->findAll();

        // Pre-fill dates
        $dateStr = $request->query->get('date');
        if ($dateStr) {
            $selectedDate = new \DateTime($dateStr);
            $course->setStartDate($selectedDate);
            $course->setEndDate($selectedDate);
        }

        $form = $this->createForm(CourseForm::class, $course);
        $form->handleRequest($request);

        // Vérif coursePeriod
        foreach ($coursePeriod as $cp) {
            if ($cp->getStartDate() <= $course->getStartDate() && $cp->getEndDate() >= $course->getStartDate()) {
                $course->setCoursePeriod($cp);
            }
        }
        if (!$course->getCoursePeriod()) {
            $this->addFlash('error', 'Aucune période de cours trouvée pour cette date.');
            return $this->render('admin/courses/view_course.html.twig', [
                'form' => $form,
                'course' => $course,
            ]);
        }

        $hasErrors = false;

        // Titre > 255 caractères
        if ($form->get('title')->getData() && strlen($form->get('title')->getData()) > 255) {
            $this->addFlash('error', 'Le titre ne doit pas dépasser 255 caractères.');
            $hasErrors = true;
        }

        // Dates + durée 4h
        if ($form->get('startDate')->getData() && $form->get('endDate')->getData()) {
            $startDate = $form->get('startDate')->getData();
            $endDate = $form->get('endDate')->getData();
            if ($startDate > $endDate) {
                $this->addFlash('error', 'La date de début doit être antérieure à la date de fin.');
                $hasErrors = true;
        $submitted = $form->isSubmitted();
        if ($submitted && !$form->isValid()) {
            /*if (!$form->get('startDate')->getData()) {
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
            $interval = $startDate->diff($endDate);
            if ($interval->h > 4) {
                $this->addFlash('error', 'L\'intervention ne doit pas dépasser 4h.');
                $hasErrors = true;
            }
        }

        // Intervenants/module
        $module = $form->get('module')->getData();
        $instructors = $form->get('CourseInstructor')->getData();
        foreach ($instructors as $instructor) {
            if (!$instructor->getModule()->contains($module)) {
                $this->addFlash('error', 'L\'intervenant ' . $instructor->getUser()->getLastName() . ' n\'intervient pas sur le module ' . $module->getName() . '. Veuillez choisir un intervenant qui intervient sur ce module.');
                $hasErrors = true;
            if ($form->get('remotely')->getData() === null) {
                $this->addFlash('error', 'Le mode (présentiel/à distance) est obligatoire.');
            }*/
            if ($form->get('startDate')->getData() && $form->get('endDate')->getData()) {
                $startDate = $form->get('startDate')->getData();
                $endDate = $form->get('endDate')->getData();
                if ($startDate > $endDate) {
                    $this->addFlash('error', 'La date de début doit être antérieure à la date de fin.');
                }
            }
        }

        // Champs obligatoires
        if ($form->isSubmitted()) {
            if (!$form->get('startDate')->getData()) $this->addFlash('error', 'La date de début est obligatoire.');
            if (!$form->get('endDate')->getData()) $this->addFlash('error', 'La date de fin est obligatoire.');
            if (!$form->get('module')->getData()) $this->addFlash('error', 'Le module est obligatoire.');
            if (!$form->get('interventionType')->getData()) $this->addFlash('error', 'Le type d\'intervention est obligatoire.');
            if ($form->get('CourseInstructor')->getData()->isEmpty()) $this->addFlash('error', 'Au moins un intervenant est obligatoire.');
            if ($form->get('remotely')->getData() === null) $this->addFlash('error', 'Le mode est obligatoire.');
        }

        if (!$hasErrors && $form->isSubmitted() && $form->isValid()) {
            $startDate = $course->getStartDate();

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
                return $this->render('admin/courses/view_course.html.twig', [
                    'form' => $form,
                    'course' => $course,
                ]);
            }

            $entityManager->persist($course);
            $entityManager->flush();
            $this->addFlash('success', 'Intervention modifiée avec succès !');
            return $this->redirectToRoute('app_calendar_calendar');
        }

        return $this->render('admin/courses/view_course.html.twig', [
            'form' => $form,
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
