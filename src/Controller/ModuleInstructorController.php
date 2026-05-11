<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\InstructorInformationsForm;

final class ModuleInstructorController extends AbstractController
{
    #[Route(path: '/twig/instructor_informations', name: 'app_instructor_informations', methods: ['GET','POST'])]
    public function instructorInformations(Request $request): Response
    {
        $form = $this->createForm(InstructorInformationsForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();
            // Traitez les données du formulaire ici (par exemple, en les enregistrant dans la base de données)
            $this->addFlash('success', 'Informations de l\'intervenant enregistrées avec succès !');
        }

        return $this->render('module_instructor/instructor_informations.html.twig', [
            'instructorInformationsForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/twig/instructor_interventions', name: 'app_instructor_interventions', methods: ['GET'])]
    public function instructorInterventions(): Response
    {
        return $this->render('module_instructor/instructor_interventions.html.twig');
    }

    #[Route(path: '/twig/instructor_list', name: 'app_instructor_list', methods: ['GET'])]
    public function instructorList(): Response
    {
        return $this->render('module_instructor/instructor_list.html.twig');
    }
}