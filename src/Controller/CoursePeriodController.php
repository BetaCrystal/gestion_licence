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
    #[Route(path:'/twig/courseperiods', name:'app_courseperiods', methods:['GET'])]
    public function coursePeriods(CoursePeriodRepository $coursePeriodRepository): Response
    {
        $coursePeriods = $coursePeriodRepository->findAll();

        return $this->render('courseperiod/courseperiod_list.html.twig', [
            'coursePeriods' => $coursePeriods,
        ]);
    }

    #[Route('/twig/add_courseperiod', name: 'app_add_courseperiod', methods: ['GET', 'POST'])]
    public function addCoursePeriod(Request $request, EntityManagerInterface $entityManager, SchoolYearRepository $schoolYearRepository): Response
    {
        $coursePeriod = new CoursePeriod();
        $form = $this->createForm(CoursePeriodForm::class, $coursePeriod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($coursePeriod);
            $entityManager->flush();

            return $this->redirectToRoute('app_courseperiods');
        }

        return $this->render('courseperiod/add_courseperiod.html.twig', [
            'form' => $form,
        ]);
    }
}