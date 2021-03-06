<?php

namespace Application\CalendarBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\CalendarBundle\Entity\CalendarCategories;
use Application\CalendarBundle\Entity\AdesignCalendar;
use Application\CalendarBundle\Entity\CalendarRoot;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use ADesigns\CalendarBundle\Event\CalendarEvent;
use Application\CalendarBundle\Event\CalendarEvent;
use Application\CalendarBundle\Form\CalendarType;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Export\ExcelExport;
use Ob\HighchartsBundle\Highcharts\Highchart;

/**
 * Calendar controller.
 *
 */
class CalendarController extends Controller {

    protected function getCalendarRoot() {
        $request = $this->getRequest();
        $session = $request->getSession();
        if (!$session->has('calendar_id')) {
            $session->set('calendar_id', '1');
        }
        $id_cal = $session->get('calendar_id');
        return $id_cal;
    }

    public function getuseridction() {
        $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $user_id = $user->getId();
            //$ret['user'] = $user_id;
        } else {
            $user_id = 0;
        }
        return $user_id;
    }

    public function usecalendarAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        if ($request->getMethod() == 'POST') {
            $data = $request->request->all();
            var_dump($data);
            foreach ($request->request->all() as $req) {
                var_dump($req);
            }

            echo "post method<br>";
            $alldatas = $request->request->all();
            // $datas = $alldatas["changements_filter"];
            // $all=$this->getRequest()->request->all();// to get all POST params.
            var_dump($alldatas);
            $id_cal = $request->get('id');
            $session->set('calendar_id', '1');
        }
    }

    public function indexapydatagridAction($page = 1) {
        $session = $this->getRequest()->getSession();
        // ajoute des messages flash
        $session->set('buttonretour', 'calendar_indexadesign');
        $source = new Entity('ApplicationCalendarBundle:AdesignCalendar');

        $grid = $this->container->get('grid');
        // Attach the source to the grid
        $grid->setSource($source);

        $grid->setId('calendargrid');
        $grid->addExport(new CSVExport('CSV Export', 'Operations', array('delimiter' => ';'), 'Windows-1252'));
        $grid->addExport(new ExcelExport('Excel Export', 'Operations', array('delimiter' => ';'), 'Windows-1252'));

        $grid->setPersistence(false);
        $grid->setDefaultOrder('id', 'desc');
        // Set the selector of the number of items per page
        $grid->setLimits(array(50));


        // Set the default page
        $grid->setPage($page);
        $grid->addMassAction(new DeleteMassAction());
        $grid->setActionsColumnSize(70);
        // $grid->setDefaultFilters(array('idEnvironnement.nom:AtGroupConcat' => array('operator' => 'like')));
        $myRowActiona = new RowAction('Edit', 'calendar_adesignajax_edit', false, '_self', array('class' => "btn btn-mini btn-warning"));
        $grid->addRowAction($myRowActiona);
        $myRowAction = new RowAction('Delete', 'calendar_adesign_delete', true, '_self', array('class' => "btn btn-mini btn-danger"));
        //$myRowAction = new RowAction('Delete', 'certificatscenter_delete', true, '_self',array('class' => 'deleteme'));
        $grid->addRowAction($myRowAction);
        // Return the response of the grid to the template

        return $grid->getGridResponse('ApplicationCalendarBundle:Calendar:indexapy.html.twig');
    }

    public function indexdatagridAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'projets');

        // $entities = $em->getRepository('ApplicationRelationsBundle:Projet')->findAll();
        $entities = $em->getRepository('ApplicationRelationsBundle:Projet')->myFindAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1)/* page number */, 15/* limit per page */
        );
        $pagination->setSortableTemplate('ApplicationRelationsBundle:pagination:sortable_link.html.twig');

        $pagination->setTemplate('ApplicationRelationsBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationRelationsBundle:Projet:index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

    public function indexdatagridadesignAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('ApplicationChangementsBundle:Changements')->getJoinedBy($sort, $dir, $parameters);

        $adapter = new DoctrineORMAdapter($query);
        //$adapter->setDistinct(false);
        // sur changement categories avec filtres la page n'est peut etre
        // plus dispo (avec les sessions) !!!!!!!!!!
        // A debugger
        $pagerfanta = $this->mypager($adapter, 20);
        $nb_pages = $pagerfanta->getNbPages();
    }

    public function indexadesignAction(Request $request) {

        $em = $this->getDoctrine()->getManager();



        /*
          $startDatetime = new \DateTime();
          $startDatetime->setTimestamp("1385334000");
          $endDatetime = new \DateTime();
          $endDatetime->setTimestamp("1388790000");

          $events = $this->container->get('event_dispatcher')->dispatch(CalendarEvent::CONFIGURE, new CalendarEvent($startDatetime, $endDatetime, 1))->getEvents();
          //  var_dump($events);
          return $this->render('ApplicationCalendarBundle:Calendar:index_test.html.twig', array(
          ));
          //      exit(1);
         */
        $form = $this->createForm(new CalendarType());


        $session = $request->getSession();
        if ($request->getMethod() == 'POST') {
            $id_cal = $request->get('id');
            $session->set('calendar_id', $id_cal);
        } elseif (!$session->has('calendar_id')) {
            $session->set('calendar_id', '1');
        }

        $id_cal = $session->get('calendar_id');

        // $calendar_entity = $em->getRepository('ApplicationCalendarBundle:AdesignCalendar')->myFindNewEvent(26400,$id_cal);



        $entity_root = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->findOneById($id_cal);
        $b_days = $entity_root->getDays();
        $days = array();
        foreach ($b_days as $value) {
            $days[] = $value;
        }
//var_dump($days);

        /* $entity = $em->getRepository('ApplicationCalendarBundle:AdesignCalendar')->find(319);
          if (!$entity) {
          throw $this->createNotFoundException('Unable to find ChangementsContact entity.');
          } */


        $entity_evements = $em->getRepository('ApplicationCalendarBundle:CalendarCategories')->myFindAll($id_cal);
        return $this->render('ApplicationCalendarBundle:Calendar:index_adesign.html.twig', array(
                    'evenements' => $entity_evements,
                    'form' => $form->createView(),
                    'days' => $days,
                    'rootcal' => $entity_root));
    }

    public function dashboardAction(Request $request) {

        return $this->render('ApplicationCalendarBundle:Calendar:dashboard.html.twig', array(
        ));
    }

    /**
     * Dispatch a CalendarEvent and return a JSON Response of any events returned.
     * chargement de la page web (ajax) + update indexadesgin.html.twig
     *
     * @param Request $request
     * @return Response
     */
    public function loadjqCalendarAction(Request $request) {

        $session = $request->getSession();
        if (!$session->has('calendar_id')) {
            $session->set('calendar_id', '1');
        }
        $root_calendar = $session->get('calendar_id');
        $startDatetime = new \DateTime();
        $startDatetime->setTimestamp($request->get('start'));
        $endDatetime = new \DateTime();
        $endDatetime->setTimestamp($request->get('end'));

        $events = $this->container->get('event_dispatcher')->dispatch(CalendarEvent::CONFIGURE, new CalendarEvent($startDatetime, $endDatetime, $root_calendar))->getEvents();

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

    public function getEventCalendarAction(Request $request) {
        $data = array();
        $em = $this->getDoctrine()->getManager();
        $ret = array();

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $data['id'] = $request->get('id');
            $entity = $em->getRepository('ApplicationCalendarBundle:AdesignCalendar')->find($data['id']);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ChangementsContact entity.');
            }
            $ret['IsSuccess'] = true;
            $ret['Msg'] = 'update success';
            $data['id'] = $entity->getId();
            $entity->getBgColor(); //set the background color of the event's label
            $data['allDay'] = (boolean) $entity->getAllDay();
            $data['title'] = $entity->getTitle();
            $data['start'] = $entity->getstartDatetime()->format('Y-m-d H:i:s');
            $data['end'] = $entity->getendDatetime()->format('Y-m-d H:i:s');

            $data['className'] = $entity->getCssClass();
            $data['className'] = $entity->getCssClass();
            $data['nbfiles'] = $entity->getNbPicture();

            $data['backgroundColor'] = $entity->getBgColor();
            $data['description'] = $entity->getDescription();
            $data['textColor'] = $entity->getFgColor(); //set the foregr
            $ret['data'] = $data;
            $response = new Response(\json_encode($ret));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    public function neweventjqCalendarAction(Request $request) {

        /*   if (!$session->has('calendar_id')) {
          $id_calendar=1;
          }else {
          $id_calendar = $session->get('calendar_id');
          } */


        $d = $request->get('start');
        $f = $request->get('end');
        $timer = $request->get('timer', 60);
        $format = 'Y-m-d H:i:s';
        //    echo "d=$d\nf=$f\n";
        $start = date($format, strtotime($d));
        $end = date($format, strtotime($f));
        /* $d1 = date_format($d, $format);
          $f1 = date_format($f, $format);
         */
        //   echo "d=$start\nf=$end\n";


        $id_calendar = 1;
        $ret = array();
        $session = $request->getSession();
        if (!$session->has('calendar_id')) {
            $ret['status'] = false;
        } else {

            $em = $this->getDoctrine()->getManager();
            $new_events = $em->getRepository('ApplicationCalendarBundle:AdesignCalendar')->myFindNewEvent($timer, $id_calendar, $start, $end);
            $return_events = array();
            $response = new \Symfony\Component\HttpFoundation\Response();
            $response->headers->set('Content-Type', 'application/json');
            // var_dump($new_events);
            foreach ($new_events as $event) {
                $return_events[] = $event->toArray();
            }

            if ($return_events) {
                $ret['status'] = true;
            } else {
                $ret['status'] = false;
            }
            $ret['data'] = $return_events;
            //var_dump($return_events);
            $response->setContent(json_encode($ret));

            return $response;
            //requete qui va bien !!
        }



        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function updatejqCalendarAction(Request $request) {
        $session = $request->getSession();
        if (!$session->has('calendar_id')) {
            $session->set('calendar_id', '1');
        }
        $idroot_calendar = $session->get('calendar_id');


        $data = array();
        $em = $this->getDoctrine()->getManager();
        $ret = array();
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {

            // $all=$this->getRequest()->request->all();// to get all POST params.
            // var_dump($all);
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
                // echo "ok f";
                $f = \DateTime::createFromFormat($format, $data['end']);
            }
            /* ========================================
             *
             * NEW EVENT
             *
              ========================================= */
            if (!$data['id']) {
                $entity_root = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->find($idroot_calendar);
                $data['title'] = $request->get('title');
                $entity = new AdesignCalendar($data['title'], $d, $f);
                if ($entity_root)
                    $entity->setCalendarid($entity_root);
                //--------------------
                // Set user
                //--------------------

                $securityContext = $this->get('security.context');
                $user = $securityContext->getToken()->getUser();
                $entity->setUser($user);



                $entity->setUrl('4564');
                $bgcolor = $request->get('backgroundColor', "#94a2be");
                $classcss = $request->get('className', 'class1');
                $description = $request->get('description', 'Pas de description');
                $allday = $request->get('allDay');
                $fgcolor = $request->get('textColor', '#FFFFFF');

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
                //--------------------
                // Persit and flush
                //--------------------
                $em->persist($entity);
                $em->flush();
                $ret['IsSuccess'] = true;
                $ret['Msg'] = 'update success';
                $data['id'] = $entity->getId();
            }
            /* ========================================
             *
             * UPDATE EVENT
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
                $fgcolor = $request->get('textColor');
                if ($fgcolor) {
                    $entity->setFgColor($fgcolor); //set the foreground color of the event's label
                    $data['textColor'] = $fgcolor;
                }
                $bgcolor = $request->get('backgroundColor');
                if ($bgcolor) {

                    $data['backgroundColor'] = $bgcolor;
                    $entity->setBgColor($bgcolor);
                }
                /* fields optionnels dans le post */
                if ($description) {
                    $entity->setDescription($description);
                }
                $allday = $request->get('allDay');
                $entity->setAllDay($allday);
                if ($title)
                    $entity->setTitle($title);
                if ($classcss) {

                    $data['className'] = $classcss;
                    $entity->setCssClass($classcss);
                }
                $em->persist($entity);
                $em->flush();
                // $data['allDay']=(boolean)$allday;
                $ret['IsSuccess'] = true;
                $ret['Msg'] = 'update success';
            }
            $ret['data'] = $data;
        }

        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function editajaxCalendarAction(Request $request, $id) {

        // Recuperation de enregistrement $id
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $calendar_entity = $em->getRepository('ApplicationCalendarBundle:AdesignCalendar')->find($id);
        $editForm = $this->createForm(new CalendarType(), $calendar_entity);
        $session = $request->getSession();
        $id_cal = $session->get('calendar_id');
       

        if ($request->getMethod() == 'POST') {
          //  $formData = $this->get('request')->request->all();
            // var_dump($formData);
            $editForm->bind($request);
            if ($editForm->isValid()) {
                $em->persist($calendar_entity);
                $em->flush();
                $session = $this->getRequest()->getSession();
                $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");
                $ret['IsSuccess'] = true;
                $response = new Response(json_encode($ret));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }
        /*???? */
         if ($id_cal) {
            $entity_evements = $em->getRepository('ApplicationCalendarBundle:CalendarCategories')->myFindAll($id_cal);
        } else {
            $entity_evements = array();
        }
        return $this->render('ApplicationCalendarBundle:Calendar:edit_adesign.html.twig', array(
                    'entity' => $calendar_entity,
                    'evenements' => $entity_evements,
                    'action' => 'edit',
                    'button_submit' => 'Modifier',
                    'form' => $editForm->createView(),
        ));
    }

    /* ????*/
    public function updateajaxCalendarAction(Request $request, $id) {

        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $calendar_entity = $em->getRepository('ApplicationCalendarBundle:AdesignCalendar')->find($id);
        $editForm = $this->createForm(new CalendarType(), $calendar_entity);



        return $this->render('ApplicationCalendarBundle:Calendar:edit_adesign.html.twig', array(
                    'entity' => $calendar_entity,
                    'action' => 'edit',
                    'button_submit' => 'Modifier',
                    'form' => $editForm->createView(),
        ));
    }

    /* public function updateAjaxCalendarAction(Request $request, $id) {

      $id = $request->get('id');
      $em = $this->getDoctrine()->getManager();
      $calendar_entity = $em->getRepository('ApplicationChangementsBundle:Calendar')->find($id);
      $editForm = $this->createForm(new WdcalendarType(), $calendar_entity);

      if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {

      }

      $response = new Response(json_encode($ret));
      $response->headers->set('Content-Type', 'application/json');
      return $response;
      } */

    public function downloadAction($id) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationCalendarBundle:DocCalendar')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docchangements entity.');
        }
        $request = $this->get('request');
        $url = $request->headers->get("referer");
        $message = "\nContacter l'administrateur";
        $session = $request->getSession();
        //  $url=$session->get('buttonretour');
        if (!isset($url)) {
            $url = 'calendar_indexadesign';
        }
        //   $path = $entity->getUploadRootDir();
        $filename = $entity->getPath();
        $realname = $entity->getOriginalFilename();
        // si existe pas, tant pis on prend le nom physique
        if (!isset($realname)) {
            $realname = $filename;
        }
        $path = $this->get('kernel')->getRootDir() . "/../web/uploads/calendars/";

        // Flush in "safe" mode to enforce an Exception if keys are not unique
        //if (!file_exists($path . $filename)) {
        if (!file_exists($path . $filename)) {
            $session->getFlashBag()->add('warning', "Le fichier $realname n 'existe pas (code 1). $message");
            return new RedirectResponse($url);
        }
        if (!is_readable($path . $filename)) {
            $session->getFlashBag()->add('info', "Le fichier $realname n 'est pas lisible (code 2). $message");
            return new RedirectResponse($url);
        }


        try {
            $content = file_get_contents($path . $filename);
        } catch (\ErrorException $e) {
            $session->getFlashBag()->add('notice', "Le fichier $realname n 'existe pas (code 2). $message");
            return $this->redirect($this->generateUrl($url));
        }
        $response = new Response();

        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $realname);
        //$response->headers->set('Content-Length',filesize($filename));
        //$session = $this->getRequest()->getSession();
        $session->getFlashBag()->add('notice', "Le fichier $realname a ete téléchargé");

        $response->setContent($content);
        return $response;
        /*
          return $this->render('ApplicationChangementsBundle:errors:errorsxhtml.html.twig', array(
          'message' => "Le fichier n'existe pas 3<br>Contacter l'administrateur")); */
    }
    
    
    /*
     * 
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $calendar_entity = $em->getRepository('ApplicationCalendarBundle:AdesignCalendar')->find($id);
        $editForm = $this->createForm(new CalendarType(), $calendar_entity);
        $session = $request->getSession();
        $id_cal = $session->get('calendar_id');
        if ($id_cal) {
            $entity_evements = $em->getRepository('ApplicationCalendarBundle:CalendarCategories')->myFindAll($id_cal);
        } else {
            $entity_evements = array();
        }

        if ($request->getMethod() == 'POST') {
            //$formData = $this->get('request')->request->all();
            // var_dump($formData);
            $editForm->bind($request);
            if ($editForm->isValid()) {
                $em->persist($calendar_entity);
     */
    
//updateentityfiles
    /**
     * Deletes a Changements entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Request $request, $id) {
        
        $em = $this->getDoctrine()->getManager();
        $calendar_entity = $em->getRepository('ApplicationCalendarBundle:AdesignCalendar')->find($id);
        
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
           /* $this->get('changement.common.manager')->deleteChangement($id);*/
            return $this->check_retour();
        }
        return $this->redirect($this->generateUrl('changements_posttest'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }
    
    
      /**
     * Finds and displays a CalendarCategories entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationCalendarBundle:AdesignCalendar')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Calendar entity.');
        }
        return $this->render('ApplicationCalendarBundle:Calendar:show.html.twig', array('entity' => $entity ));
    }

    
    

}
