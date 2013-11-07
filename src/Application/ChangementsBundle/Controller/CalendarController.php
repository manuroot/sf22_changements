<?php

namespace Application\ChangementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\ChangementsBundle\Entity\Calendar;
use Application\ChangementsBundle\Form\WdcalendarType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Calendar controller.
 *
 */
class CalendarController extends Controller {

    public function indexAction(Request $request) {

        return $this->render('ApplicationChangementsBundle:Calendar:index.html.twig', array(
        ));
    }

    public function datafeedAction(Request $request) {
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $params = array();
            $params['showdate'] = $request->get('showdate');
            $params['timezone'] = $request->get('timezone');
            $params['viewtype'] = $request->get('viewtype');
            $data_query = array();
            //$request->query->get('name');
            $method = $request->query->get('method', 'list');
            $form = $this->get('request')->request->all();

            switch ($method) {
                case "add":
                    $entity = new Calendar();
                    $entity->setNom($form["CalendarTitle"]);
                    $format = 'm/d/Y H:i';
                    $d = \DateTime::createFromFormat($format, $form["CalendarStartTime"]);
                    $f = \DateTime::createFromFormat($format, $form["CalendarEndTime"]);

                    $entity->setDateFin($f);
                    $entity->setDateDebut($d);
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
                    $datas = $em->getRepository('ApplicationChangementsBundle:Calendar')->listCalendar($params['showdate'], $params['viewtype']);

                    $data_query = $datas[0];
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
            }
        }
        $response = new Response(json_encode($data_query));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

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
      return $this->render('ApplicationChangementsBundle:Calendar:edit.html.twig', array(
                    'entity' => $calendar_entity,
                    'action' => 'edit',
                    'button_submit' => 'Modifier',
                    'form' => $editForm->createView(),
        ));
    }

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
        return $this->render('ApplicationChangementsBundle:Calendar:edit.html.twig', array(
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

