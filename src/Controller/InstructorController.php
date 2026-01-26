<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\InstructorRepository;

#[Route('/twig/instructor', name: 'app_instructor')]
final class InstructorController extends AbstractController
{
    #[Route(path: '/list_instructor', name: 'instructors', methods: ['GET'])]
    public function findInstructors( InstructorRepository $InstructorRepository): Response
    {
        return $this->render('instructor/instructor_list.html.twig', [
            'instructor' => $InstructorRepository->findAllInstructor(),
        ]);
    }
}
