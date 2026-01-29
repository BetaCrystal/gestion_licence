<?php

namespace App\Controller;

use App\Form\InstructorFilterForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\InstructorRepository;
use App\Repository\UserRepository;
use App\Entity\Instructor;

#[Route('/twig/instructor')] // Route de classe (préfixe commun)
final class InstructorController extends AbstractController
{
    #[Route(path: '/enseignant/infos/{instructorId}', name: 'enseignant_infos', methods: ['GET'])]
    public function listeInterventions(Request $request, InstructorRepository $repository, UserRepository $userRepository, int $instructorId): Response
    {
        // Temporairement pour pouvoir tester vu que y'a pas la page d'avant
        $instructor = $userRepository->queryForUserInstructor($instructorId);
        if (!$instructor) {
            throw $this->createNotFoundException('Instructeur non trouvé');
        }

        $qb = $repository->queryForInfoInstructor($instructorId);
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

                    // On redirige l'utilisateur sur la page d'ajout (avec les données vides)
                    return $this->redirectToRoute('enseignant_infos', ['id' => $instructor->getId()]);
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
        ]);
    }

      #[Route('/liste/enseignant', name: 'instructors', methods: ['GET','POST'])] // Route de méthode
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
}
