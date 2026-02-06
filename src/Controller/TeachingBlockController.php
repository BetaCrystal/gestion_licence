<?php

namespace App\Controller;

use App\Form\InstructorFilterForm;  
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TeachingBlockRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Instructor;
use App\Form\TeachingBlockForm;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TeachingBlock;
use App\Form\TeachingBlockFilterForm;

#[Route('/twig/teaching_block')] // Route de classe (préfixe commun)
final class TeachingBlockController extends AbstractController
{
    #[Route(path: '/liste', name: 'teaching_block_liste', methods: ['GET','POST'])]
    public function teachingBlockListe(Request $request, TeachingBlockRepository $repository, PaginatorInterface $paginator): Response
    {

        $form = $this->createForm(TeachingBlockFilterForm::class);
        $form->handleRequest($request);

        $qb = $repository->queryForListTeachingBlock();

        if ($form->isSubmitted()) {
            if ($form->isValid()){
                $data = $form->getData();

                if (!empty($data['code'])) {
                    $qb->andWhere('tc.code LIKE :code')
                    ->setParameter('code', $data['code']);
                }

                if (!empty($data['name'])) {
                    $qb->andWhere('tc.name LIKE :name')
                    ->setParameter('name', $data['name']);
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


        return $this->render('teachingblock/teaching_block_list.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    #[Route('/teaching-blocks', name: 'teaching_blocks')]
    public function index(): Response
    {
        return $this->render('test/test.html.twig', [
            'controller_name' => 'TeachingBlockController',
        ]);
    }
    #[Route(path: '/modifier/{id}', name: 'teaching_block_modifier', methods: ['GET','POST'])]
    public function teachingBlockModifier(Request $request, TeachingBlockRepository $repository, EntityManagerInterface $manager, TeachingBlock $teachingBlock): Response
    {

        $form = $this->createForm(TeachingBlockForm::class, $teachingBlock);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    
                    // Afficher un message de succès
                    $this->addFlash(
                        'success',
                        'Élément modifié en base de données.'
                    );

                    $manager->flush();

                } catch (\Exception $exception) {
                    $this->addFlash(
                        'danger',
                        'Une erreur est survenue lors de l\'enregistrement'
                    );
                }
            } else {
                // Afficher des messages d'erreur
                $this->addFlash(
                    'danger',
                    'Formulaire invalide'
                );
            }
        }

        return $this->render('teachingblock/teaching_block_change.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
