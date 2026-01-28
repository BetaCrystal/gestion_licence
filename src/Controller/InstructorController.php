<?php

namespace App\Controller;

use App\Form\InstructorFilterForm;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\InstructorRepository;

#[Route('/twig/instructor')] // Route de classe (préfixe commun)
class InstructorController extends AbstractController
{
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
