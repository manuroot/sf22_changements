<?php

namespace Application\ChangementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\ChangementsBundle\Entity\Calendar;
//use Application\ChangementsBundle\Entity\CalendarEvenements;
//use Application\ChangementsBundle\Entity\AdesignCalendar;
use Application\ChangementsBundle\Form\WdcalendarType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use ADesigns\CalendarBundle\Event\CalendarEvent;
use Application\CalendarBundle\Event\CalendarEvent;

/**
 * Calendar controller.
 *
 */
class AdesignCalendarController extends Controller {

    public function getuseridction(Request $request) {
        $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $user_id = $user->getId();
            //$ret['user'] =  $user_id;
        } else {
            $user_id = 0;
        }
        return $user_id;
    }

  

    public function indexadesignchangementsAction(Request $request) {
           $em = $this->getDoctrine()->getManager();
  $entity_evements = $em->getRepository('ApplicationChangementsBundle:ChangementsStatus')->findall();
   
        return $this->render('ApplicationChangementsBundle:AdesignCalendar:index_adesign_changements.html.twig', array(
        'evenements'=> $entity_evements));
    }

  
    

    public function loadjqCalendarChangementsAction(Request $request) {
        $startDatetime = new \DateTime();
        $startDatetime->setTimestamp($request->get('start'));
        $endDatetime = new \DateTime();
        $endDatetime->setTimestamp($request->get('end'));
        $events = $this->container->get('event_dispatcher')->dispatch(CalendarEvent::CONFIGUREA, new CalendarEvent($startDatetime, $endDatetime))->getEvents();
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $return_events = array();
        foreach ($events as $event) {
            $return_events[] = $event->toArray();
        }
        $response->setContent(json_encode($return_events));
        return $response;
    }

}

