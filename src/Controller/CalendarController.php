<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Course;
use App\Repository\CourseRepository;
use App\Repository\SchoolYearRepository;
use App\Repository\CoursePeriodRepository;
use Shuchkin\SimpleXLSXGen;

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
                'color' => $course->getInterventionType()->getColor(),
                'hour' => $course->getStartDate()->format('H:i'),
                'remotely' => $course->isRemotely()
            ];
        }

        return $this->json(data:$list);
    }

    #[Route('/week_convert?weekid={weekid}', name: '_week_convert', methods: ['GET'])]
    public function weekConvert(Request $request, CoursePeriodRepository $coursePeriodRepository, CourseRepository $courseRepository): Response
    {
        $weekId = $request->query->get('weekid');
        $courses = $courseRepository->findBy(['coursePeriod' => $weekId]);
        $list = [
            ['ID', 'Date de début', 'Date de fin', 'Instructeur', 'Module', 'Distanciel']
        ];
        $coursePeriod = $coursePeriodRepository->find($weekId);
        if (!$coursePeriod) {
            $this->addFlash('error', 'Période de cours non trouvée.');
            return $this->redirectToRoute('app_calendar_calendar');
        }
        foreach ($courses as $course) {
            $list[] = [
                $course->getId(),
                $course->getStartDate()->format('Y-m-d H:i:s'),
                $course->getEndDate()->format('Y-m-d H:i:s'),
                implode(', ', $course->getCourseInstructor()->map(fn($instructor) => $instructor->getUser()->getLastName())->toArray()),
                $course->getModule()->getName(),
                $course->isRemotely() ? 'Oui' : 'Non',
            ];
        }
        $xlsx = SimpleXLSXGen::fromArray( $list );
        $xlsx->downloadAs('books.xlsx');
        return $this->redirectToRoute('app_calendar_calendar');
    }

}