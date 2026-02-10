<?php

namespace App\Controller;

use App\Form\InstructorFilterForm;
use App\Form\InstructorInformationsForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\InstructorRepository;
use App\Repository\UserRepository;
use App\Entity\Instructor;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CourseRepository;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/twig/instructor')] // Route de classe (préfixe commun)
final class InstructorController extends AbstractController
{
    #[Route(path: '/enseignant/infos/{id}', name: 'enseignant_infos', methods: ['GET','POST'])]
    public function listeInterventions(Request $request, InstructorRepository $repository, Instructor $instructor,EntityManagerInterface $manager): Response
    {

        $qb = $repository->queryForInfoInstructor($instructor->getId());
        $results = $qb;

        $form = $this->createForm(InstructorInformationsForm::class, $instructor);
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


        return $this->render('instructor/instructor_informations.html.twig', [
            'results' => $results,
            'form' => $form->createView(),
            'instructor' => $instructor,
        ]);
    }

    #[Route(path: '/enseignant/ajout', name: 'enseignant_ajout', methods: ['GET','POST'])]
    public function ajoutInstructor(Request $request, InstructorRepository $repository, EntityManagerInterface $manager): Response
    {
        $instructor = new Instructor();

        $form = $this->createForm(InstructorInformationsForm::class, $instructor);
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


        return $this->render('instructor/instructor_addInstructor.html.twig', [
            'form' => $form->createView(),
        ]);
    }
#[Route('/list_instructor', name: 'instructors', methods: ['GET','POST'])]
public function list(Request $request, InstructorRepository $repo, PaginatorInterface $paginator): Response
{
    $form = $this->createForm(InstructorFilterForm::class);
    $form->handleRequest($request);

    $qb = $repo->findAllInstructor();


    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        if (!empty($data['last_name'])) {
            $qb->andWhere('u.lastName LIKE :lastName')
               ->setParameter('lastName', '%' . $data['last_name'] . '%');
        }

        if (!empty($data['first_name'])) {
            $qb->andWhere('u.firstName LIKE :firstName')
               ->setParameter('firstName', '%' . $data['first_name'] . '%');
        }

        if (!empty($data['email'])) {
            $qb->andWhere('u.email LIKE :email')
               ->setParameter('email', '%' . $data['email'] . '%');
        }
    }
    $page = $request->query->getInt('page', 1);
    $limit = 10;

    $pagination = $paginator->paginate($qb, $page, $limit);

    return $this->render('instructor/instructor_list.html.twig', [
        'form' => $form->createView(),
        'pagination' => $pagination,
    ]);
}

    #[Route(path: '/listeInterventions/enseignant/{id}', name: 'liste_interventions_enseignant', methods: ['GET','POST'])]
    public function listeInterventionsEnseignant(Request $request, InstructorRepository $repository, PaginatorInterface $paginator, int $id): Response
        {
            $qb = $repository->queryForList1($id);

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
        ]);
    }
}


