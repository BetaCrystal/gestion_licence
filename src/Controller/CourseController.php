<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CourseForm;

class CourseController extends AbstractController
{

    #[Route(path: '/twig/course', name: 'app_course', methods: ['GET'])]
    public function index(Course $course): Response
    {
        $course = $course->getCourse($course);
        return $this->render('intervention/index.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route(path: '/twig/add_course', name: 'app_add_course', methods: ['GET'])]
    public function addCourse(Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseForm::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($course);
            $entityManager->flush();

            return $this->redirectToRoute('app_course');
        }

        return $this->render('admin/form/form_course.html.twig', [
            'form' => $form,
        ]);
    }
}