<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 18/09/17
 * Time: 10:13 AM
 */

namespace NS\ColorAdminBundle\Controller;

use NS\ColorAdminBundle\Calendar\Event\CalendarEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CalendarController extends Controller
{
    public function loadCalendarEventsAction(Request $request)
    {
        $startDatetime = new \DateTime($request->get('start'));
        $endDatetime = new \DateTime($request->get('end'));

        $events = $this->container->get('event_dispatcher')->dispatch(CalendarEvent::CONFIGURE, new CalendarEvent($startDatetime, $endDatetime))->getEvents();

        return new JsonResponse($events);
    }
}
