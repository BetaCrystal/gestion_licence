<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\InterventionForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bridge\Doctrine\Attribute\MapEntity;



final class InterventionController extends AbstractController
{
    #[Route(path: '/interventions', name: 'interventions', methods: ['GET'])]
    public function listeProduits(Request $request, EntityManagerInterface $manager): Response
    {
        
        $form = $this->createForm(InterventionForm::class);
        $form->handleRequest($request);
        return $this->render('interventions/interventions.html.twig', [
            'form' => $form,
        ]);
    }
}