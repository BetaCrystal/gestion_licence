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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Shuchkin\SimpleXLSXGen;
use App\Form\InterventionForm;
use App\Entity\Indisponible;
use App\Form\IndisponibleForm;

#[Route('/instructor')] // Route de classe (préfixe commun)
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
    #[Route('/{id}', name: 'instructor_excel_export', methods: ['GET','POST'])] // Route de méthode
    public function excelExport(InstructorRepository $repository, Instructor $instructor)
    {
        $qb = $repository->queryForInfoExcel($instructor->getId());
        $results = $qb;

        $prenom = $results[0]['prenom'] ?? '';
        $nom = $results[0]['nom'] ?? '';
        $sheet = [];

        // Ligne 1 : nom de l'instructeur
        $sheet[] = ["Instructeur :", "$prenom $nom"];

        // Ligne 2 : ligne vide (aération)
        $sheet[] = [];

        // Ligne 3 : en-têtes du tableau
        $sheet[] = ['Module', 'Heure de début', 'Heure de fin'];

        // Lignes suivantes : données
        foreach ($results as $row) {
            $sheet[] = [
                $row['module'],
                $row['startDate'],
                $row['endDate'],
            ];
        }
        $xlsx = SimpleXLSXGen::fromArray($sheet)->__toString();

        return new Response(
            $xlsx,
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="fiche_instructeur.xlsx"',
            ]
        );
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

    /*#[Route(path: '/indisponible/enseignant/{id}', name: 'indisponible_enseignant', methods: ['GET','POST'])]
    public function indisponibleEnseignant(Request $request, InstructorRepository $repository, int $id): Response
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
    }*/
}
