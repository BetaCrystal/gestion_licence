<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\TeachingBlock;

class TeachingBlockController extends AbstractController
{
    #[Route('/teaching-blocks', name: 'teaching_blocks')]
    public function index(): Response
    {
        return $this->render('test/test.html.twig', [
            'controller_name' => 'TeachingBlockController',
        ]);
    }
}
