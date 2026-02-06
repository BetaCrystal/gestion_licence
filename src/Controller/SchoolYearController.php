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
use App\Repository\CoursePeriodRepository;

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
        $schoolYearForm = $this->createForm(SchoolYearForm::class, $schoolYear);
        $schoolYearForm->handleRequest($request);

        if ($schoolYearForm->isSubmitted() && !$schoolYearForm->isValid()) {
            //Erreurs champs vides
            if (!$schoolYearForm->get('name')->getData()) {
                $this->addFlash('error', 'Le nom de l\'année scolaire est obligatoire.');
            }
            if (!$schoolYearForm->get('startDate')->getData()) {
                $this->addFlash('error', 'La date de début est obligatoire.');
            }
            if (!$schoolYearForm->get('endDate')->getData()) {
                $this->addFlash('error', 'La date de fin est obligatoire.');
            }
            if ($schoolYearForm->get('startDate')->getData() && $schoolYearForm->get('endDate')->getData()) {
                $startDate = $schoolYearForm->get('startDate')->getData();
                $endDate = $schoolYearForm->get('endDate')->getData();
                if ($startDate >= $endDate) {
                    $this->addFlash('error', 'La date de début doit être antérieure à la date de fin.');
                }
            }
        }

        if ($schoolYearForm->isSubmitted() && $schoolYearForm->isValid()) {
            $entityManager->persist($schoolYear);
            $entityManager->flush();

            return $this->redirectToRoute('app_schoolyears');
        }

        return $this->render('schoolyear/add_schoolyear.html.twig', [
            'schoolYearForm' => $schoolYearForm->createView(),
        ]);
    }

    #[Route('/twig/schoolyear?id={id}', name: 'app_view_schoolyear', methods: ['GET', 'POST'])]
    public function viewSchoolYear(int $id, SchoolYearRepository $schoolYearRepository, Request $request, CoursePeriodRepository $coursePeriodRepository, EntityManagerInterface $entityManager): Response
    {
        $coursePeriods = $coursePeriodRepository->findAll();

        $schoolYear = $schoolYearRepository->find($id);
        $schoolYearForm = $this->createForm(SchoolYearForm::class, $schoolYear);
        $schoolYearForm->handleRequest($request);

        if (!$schoolYear) {
            throw $this->createNotFoundException('School year not found');
        }

        if ($schoolYearForm->isSubmitted() && !$schoolYearForm->isValid()) {
            //Erreurs champs vides
            if (!$schoolYearForm->get('name')->getData()) {
                $this->addFlash('error', 'Le nom de l\'année scolaire est obligatoire.');
            }
            if (!$schoolYearForm->get('startDate')->getData()) {
                $this->addFlash('error', 'La date de début est obligatoire.');
            }
            if (!$schoolYearForm->get('endDate')->getData()) {
                $this->addFlash('error', 'La date de fin est obligatoire.');
            }
            if ($schoolYearForm->get('startDate')->getData() && $schoolYearForm->get('endDate')->getData()) {
                $startDate = $schoolYearForm->get('startDate')->getData();
                $endDate = $schoolYearForm->get('endDate')->getData();
                if ($startDate >= $endDate) {
                    $this->addFlash('error', 'La date de début doit être antérieure à la date de fin.');
                }
            }
            if ($coursePeriods) {
                foreach ($coursePeriods as $coursePeriod) {
                    if ($coursePeriod->getSchoolYear()->getId() === $schoolYear->getId()) {
                        $this->addFlash('error', 'Impossible de modifier l\'année scolaire car elle est associée à une semaine de cours.');
                        return $this->redirectToRoute('app_view_schoolyear', ['id' => $schoolYear->getId()]);
                    }
                }
            }
        }

        if ($schoolYearForm->isSubmitted() && $schoolYearForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Année scolaire mise à jour avec succès !');
            return $this->redirectToRoute('app_view_schoolyear', ['id' => $schoolYear->getId()]);
        }

        return $this->render('schoolyear/view_schoolyear.html.twig', [
            'schoolYear' => $schoolYear,
            'coursePeriods' => $coursePeriods,
            'schoolYearForm' => $schoolYearForm->createView(),
        ]);
    }

    #[Route('/twig/delete_schoolyear?id={id}', name: 'app_delete_schoolyear', methods: ['GET', 'POST'])]
    public function deleteSchoolYear(int $id, EntityManagerInterface $entityManager, SchoolYearRepository $schoolYearRepository, CoursePeriodRepository $coursePeriodRepository): Response
    {
        $coursePeriods = $coursePeriodRepository->findAll();

        $schoolYear = $schoolYearRepository->find($id);
        if (!$schoolYear) {
            throw $this->createNotFoundException('School year not found');
        }

        if (count($coursePeriods) > 0) {
            foreach ($coursePeriods as $coursePeriod) {
                if ($coursePeriod->getSchoolYear()->getId() === $schoolYear->getId()) {
                    $this->addFlash('error', 'Impossible de supprimer l\'année scolaire car elle est associée à une semaine de cours.');
                    return $this->redirectToRoute('app_view_schoolyear', ['id' => $schoolYear->getId()]);
                }
            }
        }

        $entityManager->remove($schoolYear);
        $entityManager->flush();

        return $this->redirectToRoute('app_schoolyears');
    }
}