<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\SchoolYear;
use App\Form\SchoolYearForm;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SchoolYearRepository;

final class SchoolYearController extends AbstractController
{
    #[Route(path:'/twig/schoolyears', name:'app_schoolyears', methods:['GET'])]
    public function schoolYears(SchoolYearRepository $schoolYearRepository): Response
    {
        $schoolYears = $schoolYearRepository->findAll();

        return $this->render('schoolyear/schoolyear_list.html.twig', [
            'schoolYears' => $schoolYears,
        ]);
    }


}