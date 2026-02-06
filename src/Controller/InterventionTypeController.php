<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\InterventionTypeRepository;
use App\Entity\InterventionType;
use Doctrine\ORM\EntityManagerInterface;

final class InterventionTypeController extends AbstractController
{
    #[Route(path: '/listeTypesInterventions', name: 'liste_types_interventions', methods: ['GET','POST'])]
    public function listeTypesInterventions(Request $request, InterventionTypeRepository $repository, PaginatorInterface $paginator): Response
    {
        $interventionTypes = $repository->findAll();

         // Pagination
         $qb = $repository->createQueryBuilder('it');
        $qb = $repository->findAll();

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $pagination = $paginator->paginate(
            $qb,
            $page,
            $limit
        );

        return $this->render('intervention_types/intervention_types.html.twig', [
            'pagination' => $pagination,
            'interventionTypes' => $interventionTypes,
        ]);
    }

    #[Route(path: '/view_intervention_type?id={id}', name: 'app_view_intervention_type', methods: ['GET','POST'])]
    public function viewTypeIntervention(int $id, InterventionTypeRepository $repository, Request $request): Response
    {
        $interventionType = $repository->find($id);
        $interventionTypeForm = $this->createForm(InterventionType::class, $interventionType);
        $interventionTypeForm->handleRequest($request);

        return $this->render('intervention_types/view_intervention_type.html.twig', [
            'interventionType' => $interventionType,
        ]);
    }

    #[Route(path: '/add_intervention_type', name: 'app_add_intervention_type', methods: ['GET','POST'])]
    public function addTypeIntervention(Request $request, EntityManagerInterface $entityManager): Response
    {
        $interventionType = new InterventionType();
        $interventionTypeForm = $this->createForm(InterventionType::class, $interventionType);
        $interventionTypeForm->handleRequest($request);

        if ($interventionTypeForm->isSubmitted() && $interventionTypeForm->isValid()) {
            $interventionType = $interventionTypeForm->getData();
            $entityManager->persist($interventionType);
            $entityManager->flush();

            return $this->redirectToRoute('liste_types_interventions');
        }

        return $this->render('intervention_types/add_intervention_type.html.twig', [
            'form' => $interventionTypeForm->createView(),
        ]);
    }
}