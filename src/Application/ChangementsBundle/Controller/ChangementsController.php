<?php

namespace Application\ChangementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\ChangementsBundle\Entity\Changements;
use Application\RelationsBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\ChangementsBundle\Form\ChangementsType;
use Application\ChangementsBundle\Form\ChangementsFilterType;
use Application\ChangementsBundle\Entity\GridExport;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use APY\DataGridBundle\Grid\Export\ExcelExport;
use Ob\HighchartsBundle\Highcharts\Highchart;

/* use Pagerfanta\Pagerfanta;
  use Pagerfanta\Adapter\DoctrineORMAdapter;
  use Pagerfanta\Exception\NotValidCurrentPageException; */

/**
 * Changements controller.
 *
 */
class ChangementsController extends Controller {
    /* ====================================================================
     * 
     *  CREATION DU PAGINATOR
     * 
      =================================================================== */

    private function createpaginator($query, $num_perpage = 5) {

        $paginator = $this->get('knp_paginator');
        $pagename = 'page'; // Set custom page variable name
        $page = $this->get('request')->query->get($pagename, 1); // Get custom page variable

        $pagination = $paginator->paginate(
                $query, $page, $num_perpage, array('pageParameterName' => $pagename,
            "sortDirectionParameterName" => "dir",
            'sortFieldParameterName' => "sort")
        );
        $pagination->setTemplate('ApplicationChangementsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $pagination;
    }

    /**
     * Create filter form and process filter request.
     *
     */
    protected function filter() {
        $message = "filter datas";
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new ChangementsFilterType());
        $filterBuilder = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindAll();

        // Reset filter
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "reset") {
            $message = "reset filtres";
            $session->remove('changementControllerFilter');
            $query = $filterBuilder;
            return array($filterForm, $query, $message);
        }

        // datas filter
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "filter") {
            $alldatas = $request->request->all();
            // if ($alldatas['submit-filter'] == 'reset'){
            $datas = $alldatas["changements_filter"];
            $message = "post datas ";
            $filterForm->bind($datas);
            if ($filterForm->isValid()) {
                $message .= " - filtre valide";
                $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $filterBuilder);
                $session->set('changementControllerFilter', $datas);
               // var_dump($query->getDql());
            } else {
                $message .= " - filtre valide";

                $query = $filterBuilder;
            }
         /*   print_r($datas);
               var_dump($query->getDql());*/
              // exit(1);
       
            return array($filterForm, $query, $message);
        } else {
            //   echo "<br>pas post datas<br>";
            // Get filter from session
            if ($session->has('changementControllerFilter')) {
                $message = "session get";
                $datas = $session->get('changementControllerFilter');
                $filterForm->bind($datas);
                $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $filterBuilder);
            }
            // ou pas
            else {
                $message = "pas de session";
                $query = $filterBuilder;
            }
           // var_dump($query);
            //   exit(1);
          //   $query_year = $em->getRepository('ApplicationChangementsBundle:Changements')->sum_appli_year(2013);
             
          //  var_dump($query_year->getDql());
           // exit(1);
            return array($filterForm, $query, $message);
        }
    }

    /*
     * POST PARAMETERS
     * // obtenir respectivement des variables GET et POST
      $request->query->get('foo');

      $request->request->get('bar', 'valeur par défaut si bar est inexistant');

     */

    /* http://symfony.com/fr/doc/current/book/http_fundamentals.html
     * 
     * 
      array(2) {
      ["changements_filter"]=>
      array(8) {
      ["nom"]=>
      array(2) {
      ["condition_pattern"]=>
      string(1) "4"
      ["text"]=>
      string(2) "gg"
      }
      ["dateDebut"]=>
      array(2) {
      ["left_date"]=>
      string(0) ""
      ["right_date"]=>
      string(0) ""
      }
      ["dateFin"]=>
      array(2) {
      ["left_date"]=>
      string(0) ""
      ["right_date"]=>
      string(0) ""
      }
      ["idProjet"]=>
      string(0) ""
      ["demandeur"]=>
      string(2) "14"
      ["idStatus"]=>
      string(0) ""
      ["idEnvironnement"]=>
      string(0) ""
      ["_token"]=>
      string(40) "c98b2b23271469a4774b27226d46a3d46e6772b4"
      }
      ["submit-filter"]=>
      string(6) "filter"
     */

    
    
     
         public function indexpostAction(Request $request) {

                $message = "filter datas";
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
     
     //   list($filterForm, $queryBuilder, $message) = $this->filter();
           $queryBuilder = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindsimpleAll();
     

        $session->getFlashBag()->add('warning', "$message");

        $pagination = $this->createpaginator($queryBuilder, 5);
        return $this->render('ApplicationChangementsBundle:Changements:indexsimple.html.twig', array(
                   // 'search_form' => $filterForm->createView(),
                    'pagination' => $pagination,
        ));
    }
    public function indexcpostAction(Request $request) {

        list($filterForm, $queryBuilder, $message) = $this->filter();
        
        
        $session = $request->getSession();

        $session->getFlashBag()->add('warning', "$message");

        $pagination = $this->createpaginator($queryBuilder, 10);
        return $this->render('ApplicationChangementsBundle:Changements:indexpost.html.twig', array(
                    'search_form' => $filterForm->createView(),
                    'pagination' => $pagination,
        ));
    }

    /*
     * GET PARAMETERS
     */

    public function indexAction() {

        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'changements');
        $em = $this->getDoctrine()->getManager();
        $searchForm = $this->createForm(new ChangementsFilterType());
        $filterBuilder = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindAll();
        if ($this->get('request')->query->has('submit-filter')) {
             $searchForm->bind($this->get('request'));
            // $datas=$this->get('request')->query;
            /*  print_r($datas); */
            $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($searchForm, $filterBuilder);
            // var_dump($filterBuilder->getDql());exit(1);
        } else {
            $em = $this->getDoctrine()->getManager();
            $query = $filterBuilder;
        }

        $pagination = $this->createpaginator($query, 5);
        return $this->render('ApplicationChangementsBundle:Changements:index.html.twig', array(
                    'search_form' => $searchForm->createView(),
                    'pagination' => $pagination,
        ));
        // Chart
    }

    public function indexchartsAction() {

        $series = array(
            array("name" => "Data Serie Name", "data" => array(1, 2, 4, 5, 6, 3, 8))
        );

        $ob1 = new Highchart();
        $ob1->chart->renderTo('linechart1');  // The #id of the div where to render the chart
        $ob1->title->text('Demandes 2013');
        $ob1->xAxis->title(array('text' => "Demandes"));
        $ob1->yAxis->title(array('text' => "Mois (2013)"));
        $ob1->series($series);


        $ob2 = new Highchart();
        $ob2->chart->renderTo('linechart2');
        $ob2->title->text('Demandes 2013: Projets');
        $ob2->plotOptions->pie(array(
            'allowPointSelect' => true,
            'cursor' => 'pointer',
            'dataLabels' => array('enabled' => false),
            'showInLegend' => true
        ));
        
       
        $em = $this->getDoctrine()->getManager();
      
        $data = $em->getRepository('ApplicationChangementsBundle:Changements')->sum_appli_year();
 $data_month = $em->getRepository('ApplicationChangementsBundle:Changements')->sum_appli_monthyear();
 
 // print_r($datas);
       //     var_dump($data_month->getDql());
               
                  $res=$data_month->getResult();
                  
                  
              /*    $categories = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

$ob3 = new Highchart();
$ob3->chart->renderTo('container'); // The #id of the div where to render the chart
$ob3->chart->type('column');
$ob3->title->text('Average Monthly Weather Data for Tokyo');
$ob3->xAxis->categories($categories);
$ob3->yAxis($yData);
$ob3->legend->enabled(false);
$formatter = new Expr('function () {
                 var unit = {
                     "Rainfall": "mm",
                     "Temperature": "degrees C"
                 }[this.series.name];
                 return this.x + ": <b>" + this.y + "</b> " + unit;
             }');
$ob3->tooltip->formatter($formatter);
$ob3->series($series);

*/

                   $ob4 = new Highchart();
        $ob4->chart->renderTo('linechart4');
     //   $ob4->chart->type('bar');
        
         $ob4->plotOptions->bar(array(
         //    'type'=>'bar',
          'allowPointSelect' => true,
            'cursor' => 'pointer',
            'dataLabels' => array('enabled' => false),
            'showInLegend' => true,
             'series' => array('stacking'=> 'normal'),
              
        ));
       //  $ob4->plotOptions->series(array('stacking'=> 'normal'));
        $ob4->title->text('Demandes 2013: Pr1ojets');
        $ob4->legend->backgroundColor('#FFFCCE');
          $ob4->legend->reverse(true);
          //'reversed'=> true
            
        //));
        $ob4->xAxis->categories(array('Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai'));
         $ob4->yAxis->min(0);
         // $ob4->yAxis->max(5);
         $ob4->yAxis->title(array('text'=>'gdfgdf'));
                 
     $series4=array(
            array("name" => "Data1",
                "data" => array(1, 2, 4, 7, 6 )),
          array("name" => "Data2",
                "data" => array(2, 4, 7, 1, 4)),
         
            array("name" => "Data",
                "data" => array(3, 5, 6, 5, 3 )
                ),
         
        );
     $ob4->series($series4);
     // $ob4->series(array(array('type' => 'bar', 'name' => 'Browser share', 'data' => $series4)));
    //   $ob4->series($series4);
        //$ob4->xAxis->title()
        /*$ob4->plotOptions->bar(array(
            series =>
                    stacking: 'normal'
            'allowPointSelect' => true,
            'cursor' => 'pointer',
            'dataLabels' => array('enabled' => false),
            'showInLegend' => true
        ));*/
        




            /* $res=$data_month->getQuery()->getDql();
             echo "test<br>";
             print_r($res);*/
             
             // Printing the SQL with real values
              /*
$vals = $data_month->getFlattenedParams();
foreach(explode('?', $data_month->getSqlQuery()) as $i => $part) {
    $sql = (isset($sql) ? $sql : null) . $part;
    if (isset($vals[$i])) $sql .= $vals[$i];
}

echo $sql;*/
              /* 
               $query = sprintf("SELECT s FROM BundleName:EntityClass s where s.field1 = %d and s.field2=%d", $field1, $field2);
$em = $this->getDoctrine()->getEntityManager();*/
//$queryObj = $em->createQuery($data_month);
//$xentities = $data_month;

             //  $data_month->execute();
        /*
        $data = array(
            array('CDR', 45.0),
            array('CAC', 26.8),
            array('PGEN', 12.8),
            array('SDF/SDC', 8.5),
            array('LOME', 6.2),
            array('Others', 0.7),
        );*/
        $ob2->series(array(array('type' => 'pie', 'name' => 'Browser share', 'data' => $data)));

        return $this->render('ApplicationChangementsBundle:Changements:indexcharts.html.twig', array(
                    'chart1' => $ob1,
                    'chart2' => $ob2,
              'chart4' => $ob4
        ));
    }

    public function indexdashboardAction() {


        return $this->render('ApplicationChangementsBundle:Changements:indexdashboard.html.twig', array(
        ));
    }

    public function calendarAction() {

        //     $past = date('Y-m-d', strtotime('-30days'));
        //     $current_date=date('Y-m-d');
        //   $value = $date->toString('yyyy-MM');
        //      $currenta = ($row->getField('endTime')->format('Y-m-d'));
        //$current = date('Y-m-d', strtotime('+30days'));
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $dataform = $this->get('request')->get('form');
            $data = $dataform['publishedAt'];
            // print_r($data);
            // print_r($data['publishedAt']);
            //     exit(1);
            $current_year = $data['year'];
            $current_month = $data['month'];
            $current_yearmonth = ("$current_year-$current_month");
            $form = $this->createCalendarForm(array('mois' => $current_month, 'annee' => $current_year));
            $form->bindRequest($request);

            // print_r($data);
            //    exit(1);
        } else {
            $current_date = new \DateTime();
            //   $next=
            $current_yearmonth = $current_date->format('Y-m');
            $current_year = $current_date->format('Y');
            $current_month = $current_date->format('m');
            $form = $this->createCalendarForm(array('mois' => $current_month, 'annee' => $current_year));
        }
        //   $postData = $request->request->get('contact');
//$name_value = $postData['name'];
        // }
        //$current=$current_date->format('Y-m-d H:i:s');
        //$current=$current_date->format('Y-m-d');
        //$annee=$current->toString('m');
        //echo "current=$current annee=$annee<br>";
        //    echo "current=$current_year month=$current_month<br>";
        //   exit(1);
        $current = new \DateTime($current_yearmonth);
        $past = new \DateTime("2013-05");
        //$current = new \DateTime($row->getField('endTime')->format('Y-m-d'));

        /* $em = $this->getDoctrine()->getManager();

          $entities = $em->getRepository('ApplicationChangementsBundle:Changements')->findAll();

          return $this->render('ApplicationChangementsBundle:Changements:index.html.twig', array(
          'entities' => $entities,
          )); */
//$factory = new CalendR\Calendar;
//$factory->getEventManager()->addProvider('myawesomeprovider', 'new MyAwesomeProvider');
        //  $f=$this->get('booking_repository');
        //  $month = $f->getMonth(2012, 6);



        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'changements');
        $em = $this->getDoctrine()->getManager();

        $query_events = $em->getRepository('ApplicationChangementsBundle:Changements')
                ->getEventsQueryBuilder($past, $current);
        /* $nb_events=$em->getRepository('ApplicationChangementsBundle:Changements')
          ->findcount($past, $current); */
        //    print_r($query_events);
        // exit(1);
        // $nbtags = $query->getPicture()->count();
        $month = $this->get('calendr')->getMonth($current_year, $current_month);
        $events = $this->get('calendr')->getEvents($month);
        //  $paginator = $this->get('knp_paginator');

        return $this->render('ApplicationChangementsBundle:Changements:calendar.html.twig', array(
                    'month' => $this->get('calendr')->getMonth($current_year, $current_month),
                    // 'myweek' =>  $this->get('calendr')->getWeek(2012, 14),
                    'events' => $query_events,
                    'evenement' => $events,
                    'form' => $form->createView(),
                        // 'current_month' => $month
        ));
    }

//==============================================
    // VIEW ALL ACTEURS
    //==============================================
    public function indexapyAction($page = 1) {

        $session = $this->getRequest()->getSession();
        // ajoute des messages flash
        $session->set('buttonretour', 'changements_apy');
        $source = new Entity('ApplicationChangementsBundle:Changements');


        $source->manipulateRow(
                function ($row) {
                    // Don't show the row if the price is greater than $maxPrice
                    //  $past = date('Y-m-d');
                    $next = date('Y-m-d', strtotime('+5days'));
                    $currenta = ($row->getField('dateDebut')->format('Y-m-d'));
                    $current = date('Y-m-d', strtotime($currenta));
                    //$current = new \DateTime($row->getField('endTime')->format('Y-m-d'));
                    //$current = date('Y-m-d', strtotime($row->getField('endTime')));
                    /* if ($current < $past) {
                      $row->setColor('#fddddd');
                      } */
                    //elseif ($current < $next) {
                    if ($current < $next) {
                        $row->setColor('#fcf8e3');
                    }

                    return $row;
                }
        );

        $grid = $this->container->get('grid');
        // Attach the source to the grid
        $grid->setSource($source);

        $grid->setId('changementsgrid');
        //chiant si error
        /*  $grid->addExport(new ExcelExport('Excel Export','changements.xls',array(),'Windows-1252'));
          //$grid->addExport(new ExcelExport($title, $fileName, $params, $charset, $role));
          $grid->addExport(new GridExport('CSV Export in French', 'export', array('delimiter' => ';'), 'Windows-1252'));
          // $grid->addExport(new GridExport('CSV Export', 'export')); */
        $grid->setPersistence(false);
        $grid->setDefaultOrder('id', 'desc');
        // Set the selector of the number of items per page
        $grid->setLimits(array(10));

        // Set the default page
        $grid->setPage($page);
        $grid->addMassAction(new DeleteMassAction());
        $grid->setActionsColumnSize(70);
        $myRowActiona = new RowAction('Edit', 'changements_edit', false, '_self', array('class' => "btn btn-mini btn-warning"));
        $grid->addRowAction($myRowActiona);
        $myRowAction = new RowAction('Delete', 'changements_delete', true, '_self', array('class' => "btn btn-mini btn-danger"));
        //$myRowAction = new RowAction('Delete', 'certificatscenter_delete', true, '_self',array('class' => 'deleteme'));
        $grid->addRowAction($myRowAction);
        // Return the response of the grid to the template
        return $grid->getGridResponse('ApplicationChangementsBundle:Changements:indexapy.html.twig');
    }

    /**
     * Finds and displays a Changements entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationChangementsBundle:Changements:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new Changements entity.
     *
     */
    public function newAction() {
        $entity = new Changements();
        $form = $this->createForm(new ChangementsType(), $entity);
        // $form->getData()->getNom()->setData('someklklm');
//$entity->setNom("tre");
        return $this->render('ApplicationChangementsBundle:Changements:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    public function newflowstartAction() {
        // form data class
        $entity = new Changements();
        $flow = $this->get('application.form.flow.new.changement');
        $flow->reset();
        return $this->redirect($this->generateUrl('changements_newflow'));
    }

    /**
     * Displays a form to create a new Changements entity.
     *
     */
    public function newflowAction() {
        // form data class
        $entity = new Changements();
        $flow = $this->get('application.form.flow.new.changement');
        //  $flow->reset();
        // must match the flow's service id
        $flow->bind($entity);

        // form of the current step
        $form = $flow->createForm();

        //    $form = $flow->createForm($entity);
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->nextStep()) {
                // form for the next step
                $form = $flow->createForm();
            } else {
                // flow finished

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $flow->reset();
                $id = $entity->getId();
                $session = $this->getRequest()->getSession();
                $session->getFlashBag()->add('warning', "Enregistrement $id ajouté avec succès");

                return $this->redirect($this->generateUrl('changements')); // redirect when done
            }
        }

        return $this->render('ApplicationChangementsBundle:Changements:newflow.html.twig', array(
                    'form' => $form->createView(),
                    'flow' => $flow,
                    'entity' => $entity,
        ));
    }

    /**
     * Creates a new Changements entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Changements();
        //$entity->setNom("tre");
        $form = $this->createForm(new ChangementsType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('changements_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationChangementsBundle:Changements:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Changements entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        }

        $editForm = $this->createForm(new ChangementsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);



        return $this->render('ApplicationChangementsBundle:Changements:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Changements entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ChangementsType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            /*  $data = $editForm->getData();
              var_dump($data);
              exit; */
            /* if ($form->get('file')->getData() != NULL) {//user have uploaded a new file
              $file = $form->get('file')->getData();//get 'UploadedFile' object
              $news->setPath($file->getClientOriginalName());//change field that holds file's path in db to a temporary value,i.e original file name uploaded by user
              } */
            $em->persist($entity);
            $em->flush();
            $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");
            $route_back = $session->get('buttonretour');
            if (isset($route_back))
                return $this->redirect($this->generateUrl($route_back, array('id' => $id)));
            else
                return $this->redirect($this->generateUrl('changements'));
        }

        return $this->render('ApplicationChangementsBundle:Changements:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Changements entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Changements entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('changements'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * @Template()
     */
    public function uploadAction() {
        $document = new Document();
        $form = $this->createFormBuilder($document)
                ->add('name')
                ->add('file')
                ->getForm()
        ;

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                //  $document->upload();    
                $em->persist($document);
                $em->flush();
            }
        }
        return $this->render('ApplicationChangementsBundle:Changements:upload.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function downloadAction($filename) {
        $request = $this->get('request');
        $path = $this->get('kernel')->getRootDir() . "/../web/uploads/documents/";

        // Flush in "safe" mode to enforce an Exception if keys are not unique
        try {
            $content = file_get_contents($path . $filename);
        } catch (\ErrorException $e) {
            $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('error', "Le fichier $filename n 'existe pas");
            return $this->redirect($this->generateUrl('docchangements'));
        }


        //catches all exceptions extended from Exception (which is everything)



        /*  if (!$data = file_get_contents(file_get_contents($path.$filename))) {
          //  $content = file_get_contents($path.$filename);
          //if (!isset($content)){

          } */

        $response = new Response();

        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename);
        $session = $this->getRequest()->getSession();
        $session->getFlashBag()->add('notice', "Le fichier $filename a ete téléchargé");

        $response->setContent($content);
        return $response;
    }

    private function createCalendarForm($values = array()) {
        $year = isset($values['annee']) ? $values['annee'] : 'annee';
        $month = isset($values['mois']) ? $values['mois'] : 'mois';
        return $this->createFormBuilder()
                        /*    ->add('publishedAt', 'date', array(
                          'widget' => 'choice',
                          'empty_value' => array('year' => $year, 'month' => $month, 'day' => '1')
                          )) */
                        ->add('publishedAt', 'date', array(
                            'widget' => 'choice',
                            'format' => 'yyyy-MM-dd',
                            'pattern' => '{{ year }}-{{ month }}-{{ day }}',
                            'years' => range(Date('Y'), 2009),
                            'label' => 'Date de Recherche',
                            'input' => 'string',
                                //   'data'  => date_create()
                        ))
                        ->getForm()
        ;
    }

}

/*
     *     //     $past = date('Y-m-d', strtotime('-30days'));
        //      $currenta = ($row->getField('endTime')->format('Y-m-d'));
        //$current = date('Y-m-d', strtotime('+30days'));
        $current = new \DateTime("2013-06");
        $past = new \DateTime("2013-05");
        //$current = new \DateTime($row->getField('endTime')->format('Y-m-d'));

       
//$factory = new CalendR\Calendar;
//$factory->getEventManager()->addProvider('myawesomeprovider', 'new MyAwesomeProvider');
        //  $f=$this->get('booking_repository');
        //  $month = $f->getMonth(2012, 6);
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     *    /*    
   $filters = new Filters();

    $form = $this->createForm(new FiltersType(), $filters);

    $session = $this->getRequest()->getSession();

    if ($session->get('dql') == null) {
        $session->set('dql', "SELECT a FROM ViciousAmateurBundle:Post a WHERE a.is_active = true");
    }

    if ($request->isMethod('POST')) {
        $form->bind($request);

        if ($form->isValid()) {
            $dql = "SELECT a FROM ViciousAmateurBundle:Post a WHERE a.is_active = true";
            $country = $filters->getCountry();
            $city = $filters->getCity();
            $gender = $filters->getGender();
            $sexualOrientation = $filters->getSexualOrientation();

            if (isset($country)) {
                $dql .= " AND a.country = '" . $filters->getCountry() . "'";
            }
            if (isset($city)) {
                $dql .= " AND a.city = '" . $filters->getCity() . "'";
            }
            if (isset($gender)) {
                $dql .= " AND a.gender = '" . $filters->getGender() . "'";
            }
            if (isset($sexualOrientation)) {
                $dql .= " AND a.sexual_orientation = '" . $filters->getSexualOrientation() . "'";
            }

            $session->set('dql', $dql);
        }
    }

   /*    
   $filters = new Filters();

    $form = $this->createForm(new FiltersType(), $filters);

    $session = $this->getRequest()->getSession();

    if ($session->get('dql') == null) {
        $session->set('dql', "SELECT a FROM ViciousAmateurBundle:Post a WHERE a.is_active = true");
    }

    if ($request->isMethod('POST')) {
        $form->bind($request);

        if ($form->isValid()) {
            $dql = "SELECT a FROM ViciousAmateurBundle:Post a WHERE a.is_active = true";
            $country = $filters->getCountry();
            $city = $filters->getCity();
            $gender = $filters->getGender();
            $sexualOrientation = $filters->getSexualOrientation();

            if (isset($country)) {
                $dql .= " AND a.country = '" . $filters->getCountry() . "'";
            }
            if (isset($city)) {
                $dql .= " AND a.city = '" . $filters->getCity() . "'";
            }
            if (isset($gender)) {
                $dql .= " AND a.gender = '" . $filters->getGender() . "'";
            }
            if (isset($sexualOrientation)) {
                $dql .= " AND a.sexual_orientation = '" . $filters->getSexualOrientation() . "'";
            }

            $session->set('dql', $dql);
        }
    }

  */
