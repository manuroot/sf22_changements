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
class CalendarController extends Controller {

     public function getuseridction(Request $request) {
     $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $user_id = $user->getId();
          //$ret['user'] =  $user_id;
        }else {$user_id = 0;}
        return $user_id;
}

  /**
     * Displays a form to edit an existing Changements entity.
     *
     * 
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     *
     */
    public function indexallAction(Request $request) {
        
         $em = $this->getDoctrine()->getManager();
         $entities = $em->getRepository('ApplicationChangementsBundle:Calendar')->getUsersForRequeteBuilder();
  
        return $this->render('ApplicationChangementsBundle:Calendar:indexall.html.twig', array(
            'entities'=>$entities,
        ));
    }
    
    /**
     * Displays a form to edit an existing Changements entity.
     *
     * 
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     *
     */
    public function indexAction(Request $request) {
        
         $em = $this->getDoctrine()->getManager();
         $entity = $em->getRepository('ApplicationChangementsBundle:Calendar')->getUsersForRequeteBuilder();
    /* test nb requequetes??*/
    /*    
    $em = $this->getDoctrine()->getManager();
    $d='11/8/2013';
    $datas = $em->getRepository('ApplicationChangementsBundle:Calendar')->listCalendar($d, 'week',8);
    ==> ok=1
     */
        return $this->render('ApplicationChangementsBundle:Calendar:index.html.twig', array(
        ));
    }

      public function indexadesignAction(Request $request) {

       /*   $d='1386457200';
          $f='1382824800';
           $startDatetime = new \DateTime();
        $startDatetime->setTimestamp($d);
        
        $endDatetime = new \DateTime();
        $endDatetime->setTimestamp($f);
        
    */    
        
     /*     
         $format = 'Y-m-d H:i:s';
                    $start = \DateTime::createFromFormat($format, $s);
                    $end = \DateTime::createFromFormat($format, $e);

            
            
              $entity = $em->getRepository('ApplicationChangementsBundle:AdesignCalendar')->find($id);
  if ($entity){
      $entity-> setEndDatetime($end);
 
                    $entity-> setStartDatetime($start);

                    $em->persist($entity);
                    $em->flush();
                    
                    
  }        
                    
       */             
           $em = $this->getDoctrine()->getManager();          
       $entity = $em->getRepository('ApplicationChangementsBundle:AdesignCalendar')->find(1);

      
  /*
      $entity-> setEndDatetime($end);
      $entity-> setStartDatetime($start);*/
      /*
  }*/
       /*
          $format = 'Y-m-d H:i:s';
                    $d = \DateTime::createFromFormat($format, '2013-11-07 10:00:00');
                    $f = \DateTime::createFromFormat($format, '2013-11-07 15:00:00');

     
     $entity->setStartDatetime($d);
$entity->setEndDatetime($f);
                    $em->persist($entity);
                    $em->flush();              
                    
         */           
                    
    //      $events = $this->container->get('event_dispatcher')->dispatch(CalendarEvent::CONFIGURE, new CalendarEvent($startDatetime, $endDatetime))->getEvents();
      
        return $this->render('ApplicationChangementsBundle:Calendar:index_adesign.html.twig', array(
        ));
    }
    
    /**
* Dispatch a CalendarEvent and return a JSON Response of any events returned.
*
* @param Request $request
* @return Response
*/
    public function loadjqCalendarAction(Request $request)
    {
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
    }
    
    
    
     public function updatejqCalendarAction(Request $request) {
        
         $data=array();
    
        
           $em = $this->getDoctrine()->getManager();
         
          $ret=array();
      if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
         
        
            $params = array();
            $data['id'] = $request->get('id');
            $data['end'] = $request->get('newend');
            $data['start'] = $request->get('newstart');
            
            
            
           /*  $format = 'Y-m-d H:i:s';
                    $start = \DateTime::createFromFormat($format, $s);
                    $end = \DateTime::createFromFormat($format, $e);

            */
            
              $entity = $em->getRepository('ApplicationChangementsBundle:AdesignCalendar')->find($data['id']);
  if ($entity){
      
  /*
      $entity-> setEndDatetime($end);
      $entity-> setStartDatetime($start);*/
      /*
  }*/
       $format = 'Y-m-d H:i:s';
                    $d = \DateTime::createFromFormat($format,  $data['start']);
                    $f = \DateTime::createFromFormat($format,  $data['end']);

     
     $entity->setStartDatetime($d);
$entity->setEndDatetime($f);
                    $em->persist($entity);
                    $em->flush();             
                    

                    $ret['data']=$data;
                    $ret['IsSuccess'] = true;
                    $ret['Msg'] = 'update success';
  }
              
            
        }
       
        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    
    
    
    
    
    
    
    
    
    /**
     * Displays a form to edit an existing Changements entity.
     *
     * 
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     *
     */
    public function datafeedAction(Request $request) {
        
        
        $ret['user'] =null;
           $em = $this->getDoctrine()->getManager();
           $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $user_id = $user->getId();
          //$ret['user'] =  $user_id;
        }else {$user_id = 0;}
        
        
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
         
            $params = array();
            $params['showdate'] = $request->get('showdate');
            $params['timezone'] = $request->get('timezone');
            $params['viewtype'] = $request->get('viewtype');
            $data_query = array();
            //$request->query->get('name');
            $method = $request->query->get('method', 'list');
            $form = $this->get('request')->request->all();
             $ret['form'] = $form;
             $ret['form']['action'] ='datafeed';
            switch ($method) {
                case "add":
                    $entity = new Calendar();
                    $entity->setNom($form["CalendarTitle"]);
                    $format = 'm/d/Y H:i';
                    $d = \DateTime::createFromFormat($format, $form["CalendarStartTime"]);
                    $f = \DateTime::createFromFormat($format, $form["CalendarEndTime"]);

                    $entity->setDateFin($f);
                    $entity->setDateDebut($d);
                    $entity->setProprietaire($user);
                    $entity->setIsAllDayEvent($form['IsAllDayEvent']);
                    $em->persist($entity);
                    $em->flush();
//$ret['dd']="$d";$ret['ff']="$f";
                    $ret['IsSuccess'] = true;
                    $ret['Msg'] = 'add success';
                   
                    $data_query = $ret;
                    break;
                //viewtype: month, week ou day
                case "list":
                    $datas = $em->getRepository('ApplicationChangementsBundle:Calendar')->listCalendar($params['showdate'], $params['viewtype'],$user_id);

                    $data_query = $datas[0];
                    $data_query['form'] = $form;
                    $data_query['user'] =$user_id;
                    //$data_query['events']=array();
                    //   var_dump($data_query);
                    break;
                case "update":
                    $entity = $em->getRepository('ApplicationChangementsBundle:Calendar')->find($form["calendarId"]);
                    $format = 'm/d/Y H:i';
                    $d = \DateTime::createFromFormat($format, $form["CalendarStartTime"]);
                    $f = \DateTime::createFromFormat($format, $form["CalendarEndTime"]);
                    $entity->setDateFin($f);
                    $entity->setDateDebut($d);

                    $em->persist($entity);
                    $em->flush();
                    $ret['IsSuccess'] = true;
                    $ret['Msg'] = 'update success';
                    $data_query = $ret;
                    break;
            
             case "remove":
                    $entity = $em->getRepository('ApplicationChangementsBundle:Calendar')->find($form["calendarId"]);
                   if ($entity) {
                  $em->remove($entity);
                  $em->flush();
                  $ret['IsSuccess'] = true;
                    $ret['Msg'] = 'delete success';
                    $data_query = $ret;
                   }else {
                    $ret['IsSuccess'] = true;
                   $ret['Msg'] = 'delete error';}
                    $data_query = $ret;
                    break;
            }
        }
        $response = new Response(json_encode($data_query));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /*
     * TODO Connected user est propietaire
     * 
     */
    
    /**
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function editwdAction(Request $request, $id) {

        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $calendar_entity = $em->getRepository('ApplicationChangementsBundle:Calendar')->find($id);
        $editForm = $this->createForm(new WdcalendarType(), $calendar_entity);

        /* ----------------------
         * si post : update/create
          --------------------- */
        if ($request->getMethod() == 'POST') {
            //$formData = $this->get('request')->request->all();
            //  var_dump($formData);
            $editForm->bind($request);
            if ($editForm->isValid()) {
                $em->persist($calendar_entity);
                $em->flush();
                $session = $this->getRequest()->getSession();
                $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");
            } 
        }
      return $this->render('ApplicationChangementsBundle:Calendar:edit_create.html.twig', array(
                    'entity' => $calendar_entity,
                    'action' => 'edit',
                    'button_submit' => 'Modifier',
                    'form' => $editForm->createView(),
        ));
    }
  /**
     * Displays a form to edit an existing Changements entity.
     *
     * 
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     *
     */
    public function newAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $calendar_entity = new Calendar();
        $form = $this->createForm(new WdCalendarType(), $calendar_entity);
        /* ----------------------
         * si post : update/create
          --------------------- */
        if ($request->getMethod() == 'POST') {
            //$formData = $this->get('request')->request->all();

            $form->bind($request);

            if ($form->isValid()) {
                $em->persist($calendar_entity);
                $em->flush();
                $session = $this->getRequest()->getSession();
                $session->getFlashBag()->add('warning', "Enregistrement ajouté");
            } 
        }
        return $this->render('ApplicationChangementsBundle:Calendar:edit_create.html.twig', array(
                    'entity' => $calendar_entity,
                    'action' => 'create',
                    'button_submit' => 'Ajouter',
                    'form' => $form->createView(),
        ));
    }

    public function showXhtmlAction(Request $request) {
        $id = $request->get('id');

        // var_dump($id);
        $em = $this->getDoctrine()->getManager();
        $calendar_entity = $em->getRepository('ApplicationChangementsBundle:Calendar')->find($id);
        /*
          $response = new Response(json_encode($calendar_entity));
          $response->headers->set('Content-Type', 'application/json');
          return $response; */


        return $this->render('ApplicationChangementsBundle:Calendar:showxhtml.html.twig', array(
                    'entity' => $calendar_entity,
        ));
    }

}

