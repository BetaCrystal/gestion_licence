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

    #[Route('/twig/add_course_period?yearid={yearid}', name: 'app_add_courseperiod', methods: ['GET', 'POST'])]
    public function addCoursePeriod(Request $request, EntityManagerInterface $entityManager, SchoolYearRepository $schoolYearRepository): Response
    {
        $schoolYear = $schoolYearRepository->find($request->query->get('yearid'));
        $coursePeriod = new CoursePeriod();
        $form = $this->createForm(CoursePeriodForm::class, $coursePeriod);
        $form->handleRequest($request);

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
    public function viewCoursePeriod(int $id, CoursePeriodRepository $coursePeriodRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $coursePeriod = $coursePeriodRepository->find($id);
        if (!$coursePeriod) {
            throw $this->createNotFoundException('Course period not found');
        }

        $schoolYear = $coursePeriod->getSchoolYear();

        $form = $this->createForm(CoursePeriodForm::class, $coursePeriod);
        $form->handleRequest($request);

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
    public function deleteCoursePeriod(int $id, CoursePeriodRepository $coursePeriodRepository, EntityManagerInterface $entityManager): Response
    {
        $coursePeriod = $coursePeriodRepository->find($id);
        if (!$coursePeriod) {
            throw $this->createNotFoundException('Course period not found');
        }

        $entityManager->remove($coursePeriod);
        $entityManager->flush();

        return $this->redirectToRoute('app_courseperiods');
    }
}