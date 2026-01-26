<?php

namespace App\Controller;

use App\Form\InstructorFilterForm;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\InstructorRepository;

#[Route('/twig/instructor', name: 'app_instructor')]
final class InstructorController extends AbstractController
{
    #[Route(path: '/list_instructor', name: 'instructors', methods: ['GET'])]
    public function findInstructors( Request $request, InstructorRepository $InstructorRepository): Response
    {
        $form = $this->createForm(InstructorFilterForm::class);
        $form->handleRequest($request);

            return $this->render('instructor/instructor_list.html.twig', [
                'instructor' => $InstructorRepository->findAllInstructor(),
                'form' => $form->createView(),
            ]);
        }
}
