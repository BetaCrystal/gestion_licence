<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\InterventionForm;
use Doctrine\DBAL\Connection;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\CourseRepository;
use App\Repository\InstructorRepository;


final class InterventionController extends AbstractController
{
    #[Route(path: '/listeInterventions', name: 'liste_interventions', methods: ['GET','POST'])]
    public function listeInterventions(Request $request, CourseRepository $repository, PaginatorInterface $paginator): Response
    {

        $form = $this->createForm(InterventionForm::class);
        $form->handleRequest($request);

        $qb = $repository->queryForList();

        if ($form->isSubmitted()) {
            if ($form->isValid()){
                $data = $form->getData();

                if (!empty($data['DateDebut'])) {
                    $qb->andWhere('c.startDate >= :dateDebut')
                    ->setParameter('dateDebut', $data['DateDebut']->format('Y-m-d H:i:s'));
                }

                if (!empty($data['DateFin'])) {
                    $qb->andWhere('c.endDate <= :dateFin')
                    ->setParameter('dateFin', $data['DateFin']->format('Y-m-d H:i:s'));
                }

                if (!empty($data['Module'])) {
                    $qb->andWhere('m.id = :module')
                    ->setParameter('module', $data['Module']->getId());
                }
            }
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $pagination = $paginator->paginate(
            $qb,
            $page,
            $limit
        );


        return $this->render('interventions/interventions.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    #[Route(path: '/listeInterventions/enseignant/{id}', name: 'liste_interventions_enseignant', methods: ['GET','POST'])]
    public function listeInterventionsEnseignant(Request $request, CourseRepository $repository, PaginatorInterface $paginator,InstructorRepository $instructorRepo, int $id): Response
        {
            $qb = $repository->queryForList1($id);
            $instructor = $instructorRepo->find($id);
             $results = $instructorRepo->queryForInfoInstructor($instructor->getId());

            $form = $this->createForm(InterventionForm::class);
            $form->handleRequest($request);



            if ($form->isSubmitted()) {
                if ($form->isValid()){
                    $data = $form->getData();

                    if (!empty($data['DateDebut'])) {
                        $qb->andWhere('c.startDate >= :dateDebut')
                        ->setParameter('dateDebut', $data['DateDebut']->format('Y-m-d H:i:s'));
                    }

                    if (!empty($data['DateFin'])) {
                        $qb->andWhere('c.endDate <= :dateFin')
                        ->setParameter('dateFin', $data['DateFin']->format('Y-m-d H:i:s'));
                    }

                    if (!empty($data['Module'])) {
                        $qb->andWhere('m.id = :module')
                        ->setParameter('module', $data['Module']->getId());
                    }
                }
            }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $pagination = $paginator->paginate(
            $qb,
            $page,
            $limit
        );


        return $this->render('interventions/interventions_list.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
            'instructor' => $instructor,
            'results' => $results,
        ]);
    }
}


