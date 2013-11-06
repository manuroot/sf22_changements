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

        $calendar_entity = $em->getRepository('ApplicationChangementsBundle:Calendar')->find($id);
        $editForm = $this->createForm(new WdcalendarType(), $calendar_entity);
        //Application\ChangementsBundle\Form\WdcalendarType

        if ($request->getMethod() == 'POST') {

            $formData = $this->_request->getPost();
            print_r($formData);
            if ($form->isValid($formData)) {
                $form_datas = $form->getValues();
                $id = (int) $form->getValue('id', 0);
                // si modif
                if (isset($id) && $id != 0) {
                    echo "id is set: $id <br>";
                    $table->modifier($form_datas);
                }
                // sinon ajout
                else {
                    echo "id is not set<br>";
                    $form_datas = $form->getValues();
                    unset($formData['id']);
                    $table->insert($form_datas);
                }
                $json = $form->getMessages();
                header('Content-type: application/json');
                $json['IsSuccess'] = true;
                echo Zend_Json::encode($json);
            } else {

                $form->populate($formData);
            }
            $this->view->form = $form;
        } else {
            echo "attente de post";
            $id = (int) $this->_request->getParam('id', null);
            // si $id modif=> populate
            if ($id > 0) {
                $table = new Application_Model_DbTable_Changements();
                $row = $table->get_row($id);
                /* if (isset($row) && isset($row['is_changement'])){
                  }
                  else {
                  $table = new Application_Model_DbTable_ChronoUserAbsence();
                  $row = $table->get_row($id);
                  } */
                // print_r($row);
                $form->populate($row);
                $form->submit->setLabel('Modifier');
            }
            // Sinon Ajout
            else {

                $form->submit->setLabel('Ajouter');
            }
            $this->view->form = $form;
        }


        //    $this->view->render('wdcalendara/editwd.phtml');
    }

    // $_GET parameters
    // $method=$request->query->get('method');
    // $method = $this->_request->getParam('method');
    //   $form = $this->_request->getParams();
//print_r($form);
    //   $db = new Application_Model_DbTable_ChronoCalendara();
    // $ret = $this->get_events($form["showdate"], $form["viewtype"]);
//$array=array()

    /* case "list":


      //  $ret = $db->listCalendar($form["showdate"], $form["viewtype"]);
      break;
      case "update":
      $ret = $db->updateCalendar($form["calendarId"], $form["CalendarStartTime"], $form["CalendarEndTime"]);
      break;
      case "remove":
      $ret = $db->delete_record($form["calendarId"]);
      break;
      case "adddetails":
      //    print_r($form);
      $st = $form["stpartdate"] . " " . $form["stparttime"];
      $et = $form["etpartdate"] . " " . $form["etparttime"];
      if (isset($form["id"])) {
      //   echo "UPDATE<br>";
      $ret = $db->updateDetailedCalendar($form["id"], $st, $et, $form["Subject"], isset($form["IsAllDayEvent"]) ? 1 : 0, $form["Description"], $form["Location"], $form["colorvalue"], $form["timezone"]);
      } else {
      //  echo "ADD DETAILS<br>";
      $ret = $db->addDetailedCalendar($st, $et, $form["Subject"], isset($form["IsAllDayEvent"]) ? 1 : 0, $form["Description"], $form["Location"], $form["colorvalue"], $form["timezone"]);
      }
      break;
      } */

    //  $ret['form'] = $form;
//return json_encode($ret); 
}

