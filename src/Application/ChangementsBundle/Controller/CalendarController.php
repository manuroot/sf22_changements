<?php

namespace Application\ChangementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\ChangementsBundle\Entity\Calendar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Calendar controller.
 *
 */
class CalendarController extends Controller {

    public function indexAction(Request $request) {

        /*  $em = $this->getDoctrine()->getManager();
          $params=array();
          $params['showdate'] = '9/26/2013';
          $params['timezone'] = "1";
          $params['viewtype'] = 'month';
         */
        //  $data_query = $em->getRepository('ApplicationChangementsBundle:Calendar')->listCalendar($params['showdate'],$params['viewtype']);


        return $this->render('ApplicationChangementsBundle:Calendar:index.html.twig', array(
        ));
    }

    /*
     * updateDetailedCalendar($id, $st, $et, $sub, $ade, $dscr, $loc, $color, $tz) {
     * 
     * event: [
      [id,
      description,
      date_debut,
      date_fin,
      alldayevent (0/1),
      0,
      0,
      color
      1,
      location,
     * description 
     * 
     */

    public function datafeedAction(Request $request) {
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $params = array();
            $params['showdate'] = $request->get('showdate');
            $params['timezone'] = $request->get('timezone');
            $params['viewtype'] = $request->get('viewtype');
            $form = $request->get('form');

            $all= $this->get('request')->request->all();


          list($data_query,$addon) = $em->getRepository('ApplicationChangementsBundle:Calendar')->listCalendar($params['showdate'], $params['viewtype']);

            /* $serializer = $container->get('jms_serializer');
              $serializer->serialize($data, 'json'); */

       //    var_dump($all);
        }

      /*  $array = array(

           $data_query,
          'issort'=>true,
          'start'=>	"01\/10\/2013 00:00",
          'end'=>	"01\/10\/2013 23:59",
          "error"=>null
          ); */
        //  $array = array($array);
        //  $response = new Response($serializer);
        $response = new Response(json_encode(array('events'=>  $data_query,
            $addon,
         /*'issort'=>true,
          'start'=>	"01\/10\/2013 00:00",
          'end'=>	"01\/10\/2013 23:59",
          "error"=>null,*/
            form=>$all
         )));
        //$response=$data_query;

        $response->headers->set('Content-Type', 'application/json');
        return $response;
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

