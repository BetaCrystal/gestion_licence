<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\SchoolYear;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SchoolYearRepository;
use App\Repository\CoursePeriodRepository;
use App\Repository\CourseRepository;
use App\Entity\CoursePeriod;
use App\Form\CoursePeriodForm;

final class CoursePeriodController extends AbstractController
{
    #[Route(path:'/twig/course_periods', name:'app_courseperiods', methods:['GET'])]
    public function coursePeriods(CoursePeriodRepository $coursePeriodRepository, SchoolYearRepository $schoolYearRepository): Response
    {
        $schoolYear = $schoolYearRepository->findCurrent();
        $coursePeriods = $coursePeriodRepository->findAll();

        return $this->render('course_period/course_period_list.html.twig', [
            'coursePeriods' => $coursePeriods,
            'schoolYear' => $schoolYear,
        ]);
    }

    #[Route('/twig/add_course_period?id={id}', name: 'app_add_courseperiod', methods: ['GET', 'POST'])]
    public function addCoursePeriod(int $id, Request $request, EntityManagerInterface $entityManager, SchoolYearRepository $schoolYearRepository): Response
    {
        $schoolYear = $schoolYearRepository->find($id);
        $coursePeriod = new CoursePeriod();
        $coursePeriod->setSchoolYear($schoolYear);
        $form = $this->createForm(CoursePeriodForm::class, $coursePeriod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            //Erreurs champs vides
            if (!$form->get('name')->getData()) {
                $this->addFlash('error', 'Le nom de la semaine de cours est obligatoire.');
            }
            if (!$form->get('startDate')->getData()) {
                $this->addFlash('error', 'La date de début est obligatoire.');
            }
            if (!$form->get('endDate')->getData()) {
                $this->addFlash('error', 'La date de fin est obligatoire.');
            }
            if ($form->get('startDate')->getData() && $form->get('endDate')->getData()) {
                $startDate = $form->get('startDate')->getData();
                $endDate = $form->get('endDate')->getData();
                if ($startDate >= $endDate) {
                    $this->addFlash('error', 'La date de début doit être antérieure à la date de fin.');
                }
            }
            if ($form->get('startDate')->getData() && $form->get('endDate')->getData()) {
                $startDate = $form->get('startDate')->getData();
                $endDate = $form->get('endDate')->getData();
                if ($startDate < $schoolYear->getStartDate() || $endDate > $schoolYear->getEndDate()) {
                    $this->addFlash('error', 'Les dates de la semaine de cours doivent être comprises dans les dates de l\'année scolaire.');
                }
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($coursePeriod);
            $entityManager->flush();

            return $this->redirectToRoute('app_view_schoolyear', ['id' => $coursePeriod->getSchoolYear()->getId()]);
        }

        return $this->render('course_period/add_course_period.html.twig', [
            'form' => $form->createView(),
            'schoolYear' => $schoolYear,
        ]);
    }

    #[Route('/twig/view_course_period?id={id}', name: 'app_view_course_period', methods: ['GET', 'POST'])]
    public function viewCoursePeriod(int $id, CoursePeriodRepository $coursePeriodRepository, Request $request, EntityManagerInterface $entityManager, CourseRepository $courseRepository): Response
    {
        $courses = $courseRepository->findAll();
        $coursePeriod = $coursePeriodRepository->find($id);
        if (!$coursePeriod) {
            throw $this->createNotFoundException('Course period not found');
        }

        $schoolYear = $coursePeriod->getSchoolYear();

        $form = $this->createForm(CoursePeriodForm::class, $coursePeriod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            //Erreurs champs vides
            if (!$form->get('name')->getData()) {
                $this->addFlash('error', 'Le nom de la semaine de cours est obligatoire.');
            }
            if (!$form->get('startDate')->getData()) {
                $this->addFlash('error', 'La date de début est obligatoire.');
            }
            if (!$form->get('endDate')->getData()) {
                $this->addFlash('error', 'La date de fin est obligatoire.');
            }
            if ($form->get('startDate')->getData() && $form->get('endDate')->getData()) {
                $startDate = $form->get('startDate')->getData();
                $endDate = $form->get('endDate')->getData();
                if ($startDate >= $endDate) {
                    $this->addFlash('error', 'La date de début doit être antérieure à la date de fin.');
                }
            }
            if ($form->get('startDate')->getData() && $form->get('endDate')->getData()) {
                $startDate = $form->get('startDate')->getData();
                $endDate = $form->get('endDate')->getData();
                if ($startDate < $schoolYear->getStartDate() || $endDate > $schoolYear->getEndDate()) {
                    $this->addFlash('error', 'Les dates de la semaine de cours doivent être comprises dans les dates de l\'année scolaire.');
                }
            }
            foreach ($courses as $course){
                if ($course->getCoursePeriod()->getId() === $coursePeriod->getId()) {
                    if ($course->getStartDate() < $form->get('startDate')->getData() || $course->getEndDate() > $form->get('endDate')->getData() || $course->getStartDate() > $form->get('endDate')->getData() || $course->getEndDate() < $form->get('startDate')->getData()) {
                        $this->addFlash('error', 'Les dates de la semaine de cours ne peuvent pas chevaucher celles d\'un cours existant.');
                    }
                }
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Semaine de cours mise à jour avec succès !');
            return $this->redirectToRoute('app_view_course_period', ['id' => $coursePeriod->getId()]);
        }

        return $this->render('course_period/view_course_period.html.twig', [
            'coursePeriod' => $coursePeriod,
            'schoolYear' => $schoolYear,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/twig/delete_course_period?id={id}', name: 'app_delete_course_period', methods: ['GET', 'POST'])]
    public function deleteCoursePeriod(int $id, CoursePeriodRepository $coursePeriodRepository, EntityManagerInterface $entityManager, CourseRepository $courseRepository): Response
    {
        $courses = $courseRepository->findAll();
        $coursePeriod = $coursePeriodRepository->find($id);
        $yearid = $coursePeriod->getSchoolYear()->getId();
        if (!$coursePeriod) {
            throw $this->createNotFoundException('Course period not found');
        }
        foreach ($courses as $course){
            if ($course->getCoursePeriod()->getId() === $coursePeriod->getId()) {
                $this->addFlash('error', 'Impossible de supprimer la semaine de cours car elle est associée à un cours.');
                return $this->redirectToRoute('app_view_course_period', ['id' => $coursePeriod->getId()]);
            }
        }

        $entityManager->remove($coursePeriod);
        $entityManager->flush();

        return $this->redirectToRoute('app_view_schoolyear', ['id' => $yearid]);
    }
}