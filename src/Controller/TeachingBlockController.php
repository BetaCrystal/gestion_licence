<?php

namespace App\Controller;

use App\Form\InstructorFilterForm;  
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\InstructorRepository;
use App\Repository\UserRepository;
use App\Entity\Instructor;

#[Route('/twig/teaching_block')] // Route de classe (préfixe commun)
final class TeachingBlockController extends AbstractController
{

}
