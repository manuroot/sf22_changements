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

        $em = $this->getDoctrine()->getManager();
        $calendar_entity = $em->getRepository('ApplicationChangementsBundle:Calendar')->find($id);
        //$form=new WdcalendarType;
        $editForm = $this->createForm(new WdcalendarType(), $calendar_entity);
        $deleteForm = $this->createDeleteForm($id);
        //return $this->check_retour();
        return $this->render('ApplicationChangementsBundle:Changements:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
        
        
        /*----------------------
       * si post : update/create
       ---------------------*/
         if ($request->getMethod() == 'POST') {
             $formData = $this->get('request')->request->all();
         $id=$formData['id'];
          if (isset($id) && $id != 0) {}
          //ajout
          else {}
            // update record 
         }
             
      /*----------------------
       * si pas post :edit
       ---------------------*/
         
         else {}
            $json['IsSuccess'] = true;
       
            
        

        //    $this->view->render('wdcalendara/editwd.phtml');
   
    }

}

