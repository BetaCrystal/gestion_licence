<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/twig/calendar', name: 'twig_calendar')]
final class CalendarController extends AbstractController
{
    #[Route('/', name: '_calendar')]
    public function index(): Response
    {
        return $this->render('calendar/calendar.html.twig');
    }
}