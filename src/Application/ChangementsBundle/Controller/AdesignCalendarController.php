<?php

namespace Application\ChangementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\ChangementsBundle\Entity\Calendar;
use Application\ChangementsBundle\Entity\AdesignCalendar;
use Application\ChangementsBundle\Form\WdcalendarType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ADesigns\CalendarBundle\Event\CalendarEvent;

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


    public function indexadesignAction(Request $request) {
        return $this->render('ApplicationChangementsBundle:AdesignCalendar:index_adesign.html.twig', array(
        ));
    }
    
    
public function indexadesignchangementsAction(Request $request) {
        return $this->render('ApplicationChangementsBundle:AdesignCalendar:index_adesign_changements.html.twig', array(
        ));
    }
    /**
     * Dispatch a CalendarEvent and return a JSON Response of any events returned.
     *
     * @param Request $request
     * @return Response
     */
    public function loadjqCalendarAction(Request $request) {
          $startDatetime = new \DateTime();
        $startDatetime->setTimestamp($request->get('start'));
        
        $endDatetime = new \DateTime();
        $endDatetime->setTimestamp($request->get('end'));
        
        $events = $this->container->get('event_dispatcher')->dispatch(CalendarEvent::CONFIGURE, new CalendarEvent($startDatetime, $endDatetime))->getEvents();
        
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        
        $return_events = array();
        
        foreach($events as $event) {
            $return_events[] = $event->toArray();    
        }
        
        $response->setContent(json_encode($return_events));
        
        return $response;
      /*  $startDatetime = new \DateTime();
        $startDatetime->setTimestamp($request->get('start'));

        $endDatetime = new \DateTime();
        $endDatetime->setTimestamp($request->get('end'));

         $format = 'Y-m-d H:i:s';
            $startDatetime = \DateTime::createFromFormat($format, $startDatetime);
            $endDatetime = \DateTime::createFromFormat($format,  $endDatetime);
            
            
        $events = $this->container->get('event_dispatcher')->dispatch(CalendarEvent::CONFIGUREJ, new CalendarEvent($startDatetime, $endDatetime))->getEvents();

        var_dump($events);
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');

        $return_events = array();

        foreach ($events as $event) {
            $return_events[] = $event->toArray();
        }

        $response->setContent(json_encode($return_events));

        return $response;*/
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
        
        foreach($events as $event) {
            $return_events[] = $event->toArray();    
        }
        
        $response->setContent(json_encode($return_events));
        
        return $response;
    
    }
    public function updatejqCalendarAction(Request $request) {

        $data = array();


        $em = $this->getDoctrine()->getManager();

        $ret = array();
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $data['id'] = $request->get('id');
            $data['end'] = $request->get('newend');
            $data['start'] = $request->get('newstart');
            $format = 'Y-m-d H:i:s';
            $d = \DateTime::createFromFormat($format, $data['start']);
            $f = \DateTime::createFromFormat($format, $data['end']);

            /*========================================
             * 
             *             NEW EVENT
             * 
             =========================================*/
            if (!$data['id']) {
                $data['title'] = $request->get('title');
                $entity = new AdesignCalendar($data['title'],$d,$f);
                $entity->setUrl('4564');
                $bgcolor= $request->get('background-color','#000000');
                $classcss= $request->get('className','class1');
                $description=$request->get('className','Pas de description');
                $allday= $request->get('allDay');
               $fgcolor= $request->get('background-color','#FFFFFF');
                 $entity->setBgColor($bgcolor); //set the background color of the event's label
                 if ($allday == 'true')
                      $entity-> setAllDay(true); 
             else
                      $entity-> setAllDay(false); 
                 $entity->setCssClass($classcss);
                  $entity->setDescription($description);
                 $entity->setFgColor($fgcolor); //set the foreground color of the event's label
                /*if ($bgcolor)
                    $entity->setBgColor($bgcolor); //set the background color of the event's label
                else 
                    $entity->setBgColor('#000000'); //set the background color of the event's label
                    
                if ($classcss)
                    $entity->setCssClass($classcss); //set the background color of the event's label
                else 
                    $entity->setCssClass('class1'); //set the background color of the event's label
            */
             
             //   $entity->setCssClass(''); // a custom class you may want to apply to event labels
                  $em->persist($entity);
                $em->flush();
                $ret['IsSuccess'] = true;
                $ret['Msg'] = 'update success';
                $data['id']=$entity->getId();

            }
             /*========================================
             * 
             *             UPDATE EVENT
             * 
             =========================================*/
        
            else{
                $entity = $em->getRepository('ApplicationChangementsBundle:AdesignCalendar')->find($data['id']);
            if ($entity) {
                //$this->getRequest()->query->all(); 
                //to get all GET params and 
                //$this->getRequest()->request->all(); to get all POST params.
                $entity->setStartDatetime($d);
                $entity->setEndDatetime($f);
                //$all= $request->all();
                $title= $request->get('title');
                $allday= $request->get('allDay');
                var_dump($title);
                 var_dump($allday);
                if ($allday == 'true')
                      $entity-> setAllDay(true); 
             else
                      $entity-> setAllDay(false); 
                if ($title)
                     $entity->setTitle($title);
                $em->persist($entity);
                $em->flush();
                $ret['IsSuccess'] = true;
                $ret['Msg'] = 'update success';
            }
            }
                $ret['data'] = $data;
                
            
        }

        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

   

}

