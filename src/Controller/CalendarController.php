<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Course;
use App\Repository\CourseRepository;

#[Route('/twig/calendar', name: 'app_calendar')]
final class CalendarController extends AbstractController
{
    #[Route('/', name: '_calendar')]
    public function index(): Response
    {
        return $this->render('calendar/calendar.html.twig', [
            'controller_name' => 'CalendarController',
        ]);
    }

    #[Route('/events', name: '_events')]
    public function events(CourseRepository $courseRepository): Response
    {
        $courses = $courseRepository->findAll();
        $list = [];
        foreach ($courses as $course) {
            $list[] = [
                'id' => $course->getId(),
                'title' => $course->getTitle(),
                'instructor' => implode(', ', $course->getCourseInstructor()->map(fn($instructor) => $instructor->getUser()->getLastName())->toArray()),
                'module' => $course->getModule()->getName(),
                'start' => $course->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $course->getEndDate()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json(data:$list);
    }


}