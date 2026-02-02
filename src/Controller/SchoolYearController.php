<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\SchoolYear;
use App\Form\SchoolYearForm;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SchoolYearRepository;

final class SchoolYearController extends AbstractController
{
    #[Route(path:'/twig/schoolyears', name:'app_schoolyears', methods:['GET'])]
    public function schoolYears(SchoolYearRepository $schoolYearRepository): Response
    {
        $schoolYears = $schoolYearRepository->findAll();

        return $this->render('schoolyear/schoolyear_list.html.twig', [
            'schoolYears' => $schoolYears,
        ]);
    }

    #[Route('/twig/add_schoolyear', name: 'app_add_schoolyear', methods: ['GET', 'POST'])]
    public function addSchoolYear(Request $request, EntityManagerInterface $entityManager): Response
    {
        $schoolYear = new SchoolYear();
        $form = $this->createForm(SchoolYearForm::class, $schoolYear);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($schoolYear);
            $entityManager->flush();

            return $this->redirectToRoute('app_schoolyears');
        }

        return $this->render('schoolyear/add_schoolyear.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/twig/schoolyear?id={id}', name: 'app_view_schoolyear', methods: ['GET', 'POST'])]
    public function viewSchoolYear(int $id, SchoolYearRepository $schoolYearRepository): Response
    {
        $schoolYear = $schoolYearRepository->find($id);

        if (!$schoolYear) {
            throw $this->createNotFoundException('School year not found');
        }

        return $this->render('schoolyear/view_schoolyear.html.twig', [
            'schoolYear' => $schoolYear,
        ]);
    }
}