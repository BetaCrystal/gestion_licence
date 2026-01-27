<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\InstructorRepository;

final class InstructorController extends AbstractController
{
    #[Route(path: '/enseignant/infos/{id}', name: 'enseignant_infos', methods: ['GET'])]
    public function listeInterventions(Request $request, InstructorRepository $repository, int $id): Response
    {

        $qb = $repository->queryForInfoInstructor($id);
        $results = $qb;


        return $this->render('instructor/instructor_informations.html.twig', [
            'results' => $results,
        ]);
    }
}
