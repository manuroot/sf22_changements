<?php

namespace Application\CalendarBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

use Application\CalendarBundle\Entity\CalendarEvenements;
use Application\CalendarBundle\Entity\AdesignCalendar;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use ADesigns\CalendarBundle\Event\CalendarEvent;
use Application\CalendarBundle\Event\CalendarEvent;

/**
 * Calendar controller.
 *
 */
class CalendarController extends Controller {

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
         
     $em = $this->getDoctrine()->getManager();
  $entity_evements = $em->getRepository('ApplicationCalendarBundle:CalendarEvenements')->findall();
           return $this->render('ApplicationCalendarBundle:Calendar:index_adesign.html.twig', array(
        'evenements'=> $entity_evements));
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

        foreach ($events as $event) {
            $return_events[] = $event->toArray();
        }

       // var_dump($return_events);
        $response->setContent(json_encode($return_events));

        return $response;
    }

  

    public function updatejqCalendarAction(Request $request) {

        $data = array();
        $em = $this->getDoctrine()->getManager();
        $ret = array();
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {

            //  $all=$this->getRequest()->request->all();// to get all POST params.
            //         var_dump($all);
            $data['id'] = $request->get('id');

            $action = $request->get('action');
            if (isset($action) && $action == "delete") {
                $entity = $em->getRepository('ApplicationCalendarBundle:AdesignCalendar')->find($data['id']);
                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find ChangementsContact entity.');
                }
                $em->remove($entity);
                $em->flush();
                $ret['id'] = $data['id'];
                $ret['status'] = 'removed';
                $response = new Response(\json_encode($ret));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }




            $data['end'] = $request->get('end');
            $data['start'] = $request->get('start');
            $format = 'Y-m-d H:i:s';
            $d = \DateTime::createFromFormat($format, $data['start']);
            // $f = \DateTime::createFromFormat($format, $data['end']);
            if (!$data['end'] || $data['end'] == "") {
                // echo "here";  
                $f = \DateTime::createFromFormat($format, $data['start']);
            } else {
                //  echo "ok f";
                $f = \DateTime::createFromFormat($format, $data['end']);
            }
            /* ========================================
             * 
             *             NEW EVENT
             * 
              ========================================= */
            if (!$data['id']) {
                $data['title'] = $request->get('title');
                $entity = new AdesignCalendar($data['title'], $d, $f);
                $entity->setUrl('4564');
                $bgcolor = $request->get('background-color', '#000000');
                $classcss = $request->get('className', 'class1');
                $description = $request->get('description', 'Pas de description');
                $allday = $request->get('allDay');
                $fgcolor = $request->get('background-color', '#FFFFFF');
                $entity->setBgColor($bgcolor); //set the background color of the event's label
                /* if ($allday == 'true')
                  $entity->setAllDay(true);
                  else
                  $entity->setAllDay(false); */
                $entity->setAllDay($allday);
                $data['allDay'] = (boolean) $allday;
                $entity->setCssClass($classcss);
                $data['className'] = $classcss;

                $entity->setDescription($description);
                $entity->setFgColor($fgcolor); //set the foreground color of the event's label
                $em->persist($entity);
                $em->flush();
                $ret['IsSuccess'] = true;
                $ret['Msg'] = 'update success';
                $data['id'] = $entity->getId();
            }
            /* ========================================
             * 
             *             UPDATE EVENT
             * 
              ========================================= */ 
            
            else {
                $entity = $em->getRepository('ApplicationCalendarBundle:AdesignCalendar')->find($data['id']);
                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find ChangementsContact entity.');
                }
                // $this->getRequest()->query->all(); 
                //to get all GET params and 
                //$this->getRequest()->request->all(); to get all POST params.
                $entity->setStartDatetime($d);
                $entity->setEndDatetime($f);
                //$all= $request->all();
                $title = $request->get('title');
                $description = $request->get('description');
                $classcss = $request->get('className', 'class1');
                /* fields optionnels dans le post */
                if ($description) {
                    $entity->setDescription($description);
                }
                $allday = $request->get('allDay');
                $entity->setAllDay($allday);
                /* var_dump($title);
                  var_dump($allday); */
                /*  if ($allday == 'true'){
                  $entity->setAllDay(true);
                  //  $data['allDay']=true;
                  }
                  else{
                  $entity->setAllDay(false);
                  //   $data['allDay']=false;
                  } */
                if ($title)
                    $entity->setTitle($title);
                if ($classcss) {

                    $data['className'] = $classcss;
                    $entity->setCssClass($classcss);
                }
                $em->persist($entity);
                $em->flush();
                //  $data['allDay']=(boolean)$allday;
                $ret['IsSuccess'] = true;
                $ret['Msg'] = 'update success';
            }
            $ret['data'] = $data;
        }

        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}

