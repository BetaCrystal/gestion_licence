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
      #[Route('/list_instructor', name: 'instructors', methods: ['GET','POST'])] // Route de méthode
      public function list(Request $request, InstructorRepository $repo): Response
      {
        $form = $this->createForm(InstructorFilterForm::class, null, [
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        $data = $form->getData();
        //dd($data);
        $lastName = $data['last_name'] ?? null;

        if (empty($lastName)) {
            $instructors = $repo->findAllInstructor();
        } else {
            $instructors = $repo->findByLastName($lastName);
        }
        return $this->render('instructor/instructor_list.html.twig', [
            'instructor' => $instructors,
            'form' => $form->createView(),
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
}
