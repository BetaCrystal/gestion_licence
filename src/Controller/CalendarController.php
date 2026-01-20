<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
    public function events(): Response
    {
        $events = [
            [
                'id' => 0,
                'title' => 'Event 1',
                'start' => '2026-01-10T10:00:00',
                'end' => '2026-01-10T12:00:00',
                'extendedProps' => [ // ne fonctionne pas, obligé de passer par une fonction en javascript
                    'module' => 'javascript',
                    'instructor' => 'John Doe',
                    'intervention' => 'cours'
                ]
            ],
            [
                'id' => 1,
                'title' => 'Event 2',
                'start' => '2026-01-15T10:00:00',
                'end' => '2026-01-15T12:00:00',
                'extendedProps' => [
                    'module' => 'php',
                    'instructor' => 'Jane Smith',
                    'intervention' => 'tp'
                ]
            ],
            [
                'id' => 2,
                'title' => 'Event 3',
                'start' => '2026-01-20T10:00:00',
                'end' => '2026-01-20T12:00:00',
                'extendedProps' => [
                    'module' => 'symfony',
                    'instructor' => 'Alice Johnson',
                    'intervention' => 'cours'
                ]
            ],
        ];

        return $this->json($events);
    }

    /*#[Route('/event-content', name: '_event_content')]
    public function eventContent(Request $request): Response
    {
        $eventContent = [
            0 => [
                'module' => 'javascript',
                'instructor' => 'John Doe',
                'intervention' => 'cours',
            ],
            1 => [
                'module' => 'php',
                'instructor' => 'Jane Smith',
                'intervention' => 'tp',
            ],
            2 => [
                'module' => 'symfony',
                'instructor' => 'Alice Johnson',
                'intervention' => 'cours',
            ],
        ];
        $id = $request->query->get('id');
        $content = $eventContent[(int)$id];

        $html = $this->renderView('calendar/_event_content.html.twig', [
            'content' => $content,
        ]);

        return new Response($html);
    }*/
}