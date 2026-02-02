<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\InterventionTypeRepository;
use App\Entity\InterventionType;

final class InterventionTypeController extends AbstractController
{
    #[Route(path: '/listeTypesInterventions', name: 'liste_types_interventions', methods: ['GET','POST'])]
    public function listeTypesInterventions(Request $request, InterventionTypeRepository $repository, PaginatorInterface $paginator): Response
    {
        $qb = $repository->queryForList();

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $pagination = $paginator->paginate(
            $qb,
            $page,
            $limit
        );

        return $this->render('intervention_types/intervention_types.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}