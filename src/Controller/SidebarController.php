<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/twig', name: 'twig_')]
final class SidebarController extends AbstractController
{
    #[Route(path:'/sidebar', name: 'sidebar_', methods: ['GET'])]
    public function twigAdmin(): Response
    {
        return $this->render('test/test.html.twig');
    }
}
