<?php

namespace App\Controller;

use App\Entity\Indisponible;
use App\Form\IndisponibleForm;
use App\Repository\IndisponibleRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/indisponible')] // Route de classe (préfixe commun)
final class InstructorController extends AbstractController
{
#[Route(path: '/indisponible/enseignant/{id}', name: 'indisponible_enseignant', methods: ['GET','POST'])]
    public function indisponibleEnseignant(Request $request, IndisponibleRepository $repository, int $id): Response
    {
        $qb = $repository->queryForIndisponible($id);

            $form = $this->createForm(IndisponibleForm::class);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()){
                    $data = $form->getData();

                    if (!empty($data['date_debut'])) {
                        $qb->andWhere('c.startDate >= :date_debut')
                        ->setParameter('date_debut', $data['date_debut']->format('Y-m-d H:i:s'));
                    }

                    if (!empty($data['date_fin'])) {
                        $qb->andWhere('c.endDate <= :date_fin')
                        ->setParameter('date_fin', $data['date_fin']->format('Y-m-d H:i:s'));
                    }

                    if (!empty($data['instructor'])) {
                        $qb->andWhere('m.id = :instructor')
                        ->setParameter('instructor', $data['instructor']->getId());
                    }
                }
            }

        return $this->render('indisponible/indisponible.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}