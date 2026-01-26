<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/twig/instructor', name: 'app_instructor')]
final class InstructorController extends AbstractController
{
    #[Route(path: '/instructors', name: 'instructors', methods: ['GET'])]
    public function findInstructors( $InstructorRepository): Response
    {
        return $this->render('instructor/instructor.html.twig', [
            'instructors' => $InstructorRepository->findAllInstructor(),
        ]);
    }
}
