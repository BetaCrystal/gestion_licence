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
}
