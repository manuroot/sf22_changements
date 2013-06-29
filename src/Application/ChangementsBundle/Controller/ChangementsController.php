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
use Application\ChangementsBundle\Form\CalendarType;
use Application\ChangementsBundle\Form\ChangementsFilterType;
use Application\ChangementsBundle\Form\ChangementsFilterAmoiType;
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
use Doctrine\ORM\Tools\Pagination\CountOutputWalker;

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
        //$paginator->setUseOutputWalkers(true);
        $pagename = 'page'; // Set custom page variable name
        $page = $this->get('request')->query->get($pagename, 1); // Get custom page variable

        $pagination = $paginator->paginate(
                $query, $page, $num_perpage, array('pageParameterName' => $pagename,
            "sortDirectionParameterName" => "dir",
            'sortFieldParameterName' => "sort")
        );
        $pagination->setTemplate('ApplicationChangementsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        $pagination->setSortableTemplate('ApplicationChangementsBundle:pagination:sortable_link.html.twig');
        return $pagination;
    }

    /**
     * Create filter form and process filter request.
     *
     */
    protected function filter() {
        //  $message = "filter datas";
        $message = "";
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new ChangementsFilterType());
        $filterBuilder = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindAll();

        //  var_dump($filterBuilder->getDql());
        // exit(1);
        //debug
        //  return array($filterForm, $filterBuilder, $message);
        // Reset filter
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "reset") {
            //  $message = "reset filtres";
            $session->remove('changementControllerFilter');
            $query = $filterBuilder;
            return array($filterForm, $query, $message);
        }

        // datas filter
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "filter") {
            $alldatas = $request->request->all();


            // if ($alldatas['submit-filter'] == 'reset'){
            $datas = $alldatas["changements_filter"];
            //var_dump($datas);exit(1);
            /* if (is_array($datas['idusers'])) {
              //     var_dump($datas['idusers']);exit(1);
              $datas['idusers'] = $datas['idusers'][0];
              } */
            // $data['idusers']=$data['idusers'][0];
            /*     var_dump($datas);
              var_dump($datas['idusers']);
              echo $datas['idusers'];
              var_dump($data['idusers']);exit(1); */
            // $message = "post datas ";
            $filterForm->bind($datas);
            if ($filterForm->isValid()) {
                // $message .= " - filtre valide";
                $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $filterBuilder);

                //   var_dump($query->getDql());exit(1);
                $session->set('changementControllerFilter', $datas);
                // var_dump($query->getDql());
            } else {
                //  $message .= " - filtre valide";

                $query = $filterBuilder;
            }
            //print_r($datas);
            //  var_dump($query->getDql());

            return array($filterForm, $query, $message);
        } else {
            //   echo "<br>pas post datas<br>";
            // Get filter from session
            if ($session->has('changementControllerFilter')) {
                // $message = "session get";
                $datas = $session->get('changementControllerFilter');
                $filterForm->bind($datas);
                $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $filterBuilder);
            }
            // ou pas
            else {
                // $message = "pas de session";
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

    public function indexpostAction(Request $request) {

        $date_warning = array(7, 15);
        $message = "";
        //$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();

        $session->set('buttonretour', 'changements_post');
        list($filterForm, $queryBuilder, $message) = $this->filter();

        //    $queryBuilder = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindsimpleAll();

        if ($message)
            $session->getFlashBag()->add('warning', "$message");

        $pagination = $this->createpaginator($queryBuilder, 15);
        return $this->render('ApplicationChangementsBundle:Changements:indexpost.html.twig', array(
                    'search_form' => $filterForm->createView(),
                    'pagination' => $pagination,
                    'date_warning' => $date_warning,
        ));
    }

    public function indexposttestAction(Request $request) {

        //  $entity = new Changements();
        $parameters = array();
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $searchForm = $this->createForm(new ChangementsFilterAmoiType());

        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "reset") {
            //  $message = "reset filtres";
            $session->remove('changementControllerFilternew');

            $query = $em->getRepository('ApplicationChangementsBundle:Changements')->getListBy(array());
        } elseif ($request->getMethod() == 'POST' && $request->get('submit-filter') == "filter") {
            $alldatas = $request->request->all();
            $datas = $alldatas["changements_searchfilter"];
            //  var_dump($datas);exit(1);
            //foreach ($datas){}
            /* foreach ($datas as $field => $value) {
              // A revoir
              //if ($this->getClassMetadata($entity)->hasField($field)) {
              // Make sure we only use existing fields (avoid any injection)
              $parameters[$field]=$value;
              // }
              } */
            $parameters = $datas;
            $session->set('changementControllerFilternew', $datas);
            $searchForm->bind($datas);

            $query = $em->getRepository('ApplicationChangementsBundle:Changements')->getListBy($parameters);
        } else {
            //   echo "<br>pas post datas<br>";
            // Get filter from session
            if ($session->has('changementControllerFilternew')) {
                // $message = "session get";
                $datas = $session->get('changementControllerFilternew');
                $parameters = $datas;
                $query = $em->getRepository('ApplicationChangementsBundle:Changements')->getListBy($parameters);
                $searchForm->bind($datas);
            }
            // ou pas
            else {
                // $message = "pas de session";
                $query = $em->getRepository('ApplicationChangementsBundle:Changements')->getListBy(array());
            }

            // 1 requete !!!!
            // $query->getQuery()->execute();

            /* return $this->render('ApplicationChangementsBundle:Changements:simple.html.twig', array(
              'search_form' => $searchForm->createView(),
              )); */
        }
        $pagination = $this->createpaginator($query, 10);
        return $this->render('ApplicationChangementsBundle:Changements:simple.html.twig', array(
                    'search_form' => $searchForm->createView(),
                    'pagination' => $pagination,
        ));
    }

    /*
     * POST PARAMETERS
     * // obtenir respectivement des variables GET et POST
      $request->query->get('foo');

      $request->request->get('bar', 'valeur par défaut si bar est inexistant');

     */

  
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

        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository('ApplicationChangementsBundle:Changements')->sum_appli_year();
        $data_month = $em->getRepository('ApplicationChangementsBundle:Changements')->sum_appli_monthyear();
        $data_sumbymonth = $em->getRepository('ApplicationChangementsBundle:Changements')->sum_allappli_bymonthyear();
        $res = $data_month->getResult();
        $res2 = $data_sumbymonth->getScalarResult();

        //   var_dump($data_sumbymonth->getDql());exit(1);
        $temp = array();
        $series_bymois = array();
        for ($i = 0; $i < 12; $i++) {
            //  echo "i=$i<br>";
            // if (array_key_exists($res2[$i]['mois'])){
            if (isset($res2[$i]['mois'])) {
                //         echo "mois=" . $res2[$i]['mois'] . "-nb=" .  $res2[$i]['nb'] . "<br>";
                //     if ((int) $v['mois'] > 0)
                $series_bymois[$i] = (int) $res2[$i]['nb'];
            } else {
                $series_bymois[$i] = null;
            }
        }
        /* foreach ($res2 as $k => $v) {
          //    array_push($series_bymois,(integer)$v['nb']);

          echo "k=$k mois=" . $v['mois'] . "nb=" . $v['nb'] . "<br>";
          $series_bymois[$k]=(integer)$v['nb'];
          // array_push($temp, $v)
          // $ymarray[$v["projet"]]=

          } */
        //  print_r($series_bymois);exit(1);
        $series = array(
            //        array("name" => "Data Serie Name", "data" =>     array(1, 2, 4, 7, 6,9))
            array("name" => "operations", "data" => $series_bymois)
        );

        $ob1 = new Highchart();
        $ob1->chart->renderTo('linechart1');  // The #id of the div where to render the chart
        $ob1->chart->type('column');
        $ob1->title->text('Demandes 2013');
//$categories = array('Jan', 'Feb', 'Mar', 'Apr', 'May');
        $categories = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        $ob1->xAxis->categories($categories);
        $ob1->xAxis->title(array('text' => "Mois (2013)"));
        /*
          $ob1->tooltip(array(
          'pointFormat'=>'<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
          'shared'=> true
          )); */

        $ob1->plotOptions->column(array(
            'stacking' => 'normal',
            'dataLabels' => array(
                'enabled' => true,
                'style' => array(
                    'fontWeight' => 'bold',
                    'color' => '(Highcharts.theme && Highcharts.theme.textColor) || "gray"',
                )
            ),
            'pointPadding' => 0.1,
            'borderWidth' => 1));
        $ob1->yAxis(array(
            'borderWidth' => 1,
            'title' => array('text' => "Opérations"),
            'stackLabels' => array(
                'enabled' => false,
                'style' => array(
                    'fontWeight' => 'bold',
                    'color' => '(Highcharts.theme && Highcharts.theme.textColor) || "gray"',
                ))
        ));
        //  $ob1->yAxis->title(array('text' => "Opérations"));
        $ob1->series($series);
        /*
          $ob1 = new Highchart();
          $ob1->chart->renderTo('linechart1'); // The #id of the div where to render the chart
          $ob1->title->text('Demandes 2013');
          $ob1->xAxis->title(array('text' => "Demandes"));
          $ob1->yAxis->title(array('text' => "Mois (2013)"));
          $ob1->series($series);

         */
        $ob2 = new Highchart();
        $ob2->chart->renderTo('linechart2');
        $ob2->title->text('Demandes 2013: Projets');


        /*  $ob2->plotOptions->pie(array(
          'allowPointSelect' => true,
          'cursor' => 'pointer',
          'dataLabels' => array('enabled' => false),
          'showInLegend' => true
          )); */
        /*
          SELECT p, SUBSTRING(p.date, 6, 2) as month
          FROM Entity p
          GROUP BY month
         */


        /*
          print_r($res2);exit(1);
          var_dump($data_sumbymonth->getDql());exit(1);
          $temp=array();

          foreach ($res as $k=>$v){
          array_push($temp,array($v['mois'],$v['nb']));
          echo "nb=" . $v['nb'] . "<br>";
          // array_push($temp, $v)
          // $ymarray[$v["projet"]]=
          } */
        // for i=1a12
        // 0 >=jan 
        //1 => fev==2 =>>val $res[c
        //   $temp[mois]=$ta
        /*
          var_dump($res);
          echo "<br>==============<br>";
          var_dump($temp);
          exit(1); */
        /*
         *  array("name" => "Data1",
          "data" => array(1, 2, 4, 7, 6,9)),
          array("name" => "Data2",
          "data" => array(2, 4, 7, 1, 4,2)),
          array("name" => "Data",
          "data" => array(3, 5, 6, 5, 3,5)
          ),
         */
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
        //$ob5 = new Highchart();
        //$ob4->plotOptions->bar(array(

        /*
          $ob4 = new Highchart();
          $ob4->chart->renderTo('linechart4');
          $ob4->chart->type('column');
          $ob4->plotOptions->bar(array(
          //   'type'=>'bar',
          'column' => array(
          'stacking' => 'normal',
          'dataLabels' => array(
          'enabled' => true,
          )
          ),
          'pointPadding' => 0.4,
          'borderWidth' => 1));

          //  $ob4->plotOptions->series(array('stacking'=> 'normal'));
          $ob4->title->text('Demandes 2013: Projets');
          $ob4->legend->backgroundColor('#FFFCCE');
          $ob4->legend->reverse(true);
          //'reversed'=> true
          //));
          $ob4->xAxis->categories(array(
          'Jan',
          'Feb',
          'Mar',
          'Apr',
          'May',
          'Jun',
          'Jul',
          'Aug',
          'Sep',
          'Oct',
          'Nov',
          'Dec'
          ));
          $ob4->yAxis->min(0);
          // $ob4->yAxis->max(5);
          $ob4->yAxis->title(array('text' => 'Total Applications'));
          $ob4->yAxis->stackLabels(array('enabled' => true,
          'style' => array('fontWeight' => 'bold',
          'color' => 'Highcharts.theme && Highcharts.theme.textColor) || "gray"')
          ));
          $series4 = $res;
          $ob4->series($series4);
         */





        /* $res=$data_month->getQuery()->getDql();
          echo "test<br>";
          print_r($res); */

        // Printing the SQL with real values
        /*
          $vals = $data_month->getFlattenedParams();
          foreach(explode('?', $data_month->getSqlQuery()) as $i => $part) {
          $sql = (isset($sql) ? $sql : null) . $part;
          if (isset($vals[$i])) $sql .= $vals[$i];
          }

          echo $sql; */
        /*
          $query = sprintf("SELECT s FROM BundleName:EntityClass s where s.field1 = %d and s.field2=%d", $field1, $field2);
          $em = $this->getDoctrine()->getManager(); */
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
          ); */
        $ob2->series(array(array('type' => 'pie', 'name' => 'Browser share', 'data' => $data)));

        return $this->render('ApplicationChangementsBundle:Changements:indexcharts.html.twig', array(
                    'chart1' => $ob1,
                    'chart2' => $ob2,
                        //  'chart4' => $ob4
        ));
    }

    public function indexdashboardAction() {


        return $this->render('ApplicationChangementsBundle:Changements:indexdashboard.html.twig', array(
        ));
    }

    public function calendarAction(Request $request) {

        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'changements');
        $datas_session = $session->get('calendar_dates');
        $surround_months=array();
        $form = $this->createForm(new CalendarType());
        if ($request->getMethod() == 'POST') {
            $dataform = $request->get('changements_calendar_form');
            //     print_r($dataform);exit(1);
            $session->set('calendar_dates', $dataform);
            $current_year = $dataform['publishedAt']['year'];
            $current_month = $dataform['publishedAt']['month'];
              /*  $next = date('Y-m-d', strtotime('+5days'));
                    $currenta = ($row->getField('dateDebut')->format('Y-m-d'));
                    $current = date('Y-m-d', strtotime($currenta));*/
                    //$current = new \DateTime($row->getField('endTime')->format('Y-m-d'));
                    //$current = date('Y-m-d', strtotime($row->getField('endTime')));
             
            //$surround_months['next']month=date('MMMM');
          //  $date=$current_year . '-' $current_month . '-01'; 
            //$surround_months['previous']= $currenta = $date->format('Y-m-d'));
            //$surround_months['previous']=;$next_month=date('MMMM');
            $form->bind($dataform);
        } elseif (isset($datas_session)) {
            //   echo "data set<br>";
            //    exit(1);
            $datas = $session->get('calendar_dates');
            $current_year = $datas['publishedAt']['year'];
            $current_month = $datas['publishedAt']['month'];
            $form->bind($datas);
        }
        // pas de sesion
        else {
          //  echo "pas de session<br>";
            //print_r($datas_session);
            //    exit(1);
            $current_date = new \DateTime();
            // $next=
            $current_yearmonth = $current_date->format('Y-m');
            $current_year = $current_date->format('Y');
            $current_month = $current_date->format('m');
           // echo "y=$current_year m=$current_month<br>";
            // exit(1);
            //   $next=
            //$current_year ='2013';
            // $current_month = '06';
            // $current_yearmonth = $current_date->format('Y-m-d');
            $datas = array();
            $datas['publishedAt'] = array(
                'year' => (int) $current_year, 'month' => (int) $current_month, 'day' => 1,
            );
            $form->bind($datas);
        }

        $month = $this->get('calendr')->getMonth($current_year, $current_month);
        $eventCollection = $this->get('calendr')->getEvents($month);
        return $this->render('ApplicationChangementsBundle:Changements:calendar.html.twig', array(
                    'month' => $month,
                    'evenement' => $eventCollection,
                    'form' => $form->createView(),
                    'year' => $current_year,
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
                  //  $next = date('Y-m-d', strtotime('+5days'));
                    $currenta = $row->getField('idStatus.nom');
                    if ($currenta == 'en cours'){
                         $row->setColor('#dff0d8;');
                    }
                    elseif ($currenta == 'en preparation') {
                           $row->setColor('#fcf8e3');
                    }
                    
                     //$current = new \DateTime($row->getField('endTime')->format('Y-m-d'));
                    //$current = date('Y-m-d', strtotime($row->getField('endTime')));
                    /* if ($current < $past) {
                      $row->setColor('#fddddd');
                      } */
                    //elseif ($current < $next) {
                  /*  if ($current < $next) {
                        $row->setColor('#fcf8e3');
                    }*/

                //    echo "current=$currenta<br>";
                    return $row;
                }
        );
        /*
        $source->manipulateRow(
                function ($row) {
                    // Don't show the row if the price is greater than $maxPrice
                    //  $past = date('Y-m-d');
                    $next = date('Y-m-d', strtotime('+5days'));
                    $currenta = ($row->getField('dateDebut')->format('Y-m-d'));
                    $current = date('Y-m-d', strtotime($currenta));
                    //$current = new \DateTime($row->getField('endTime')->format('Y-m-d'));
                    //$current = date('Y-m-d', strtotime($row->getField('endTime')));
                     if ($current < $past) {
                      $row->setColor('#fddddd');
                      } 
                    //elseif ($current < $next) {
                    if ($current < $next) {
                        $row->setColor('#fcf8e3');
                    }

                    return $row;
                }
        );*/

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

     /*   $categoriesColumn = $grid->getColumn('idEnvironnement.nom:AtGroupConcat');
      $categoryValues = array(
                   'production' => 'production',
                   'integration' => 'integration',
        );
        $categoriesColumn->setValues(
            $categoryValues
        );
        $categoriesColumn->setOperators(
            array("like","nlike","eq","neq")
        );
        
      
        */

       /* $categoriesColumn->setOperators(
            array("like")
        );*/
        
        // Set the default page
        $grid->setPage($page);
        $grid->addMassAction(new DeleteMassAction());
        $grid->setActionsColumnSize(70);
     //   $grid->setDefaultFilters(array('idEnvironnement.nom:AtGroupConcat' => array('operator' => 'like')));
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
   public function showXhtmlAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

         return $this->render('ApplicationChangementsBundle:Changements:showxhtml.html.twig', array(
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

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $flow->reset();
                $id = $entity->getId();
                $session = $this->getRequest()->getSession();
                $session->getFlashBag()->add('warning', "Enregistrement $id ajouté avec succès");

                return $this->redirect($this->generateUrl('changements_post')); // redirect when done
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

        // $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);
        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindaIdAll($id);
        //myFindIdAll($id,$criteria = array()) 
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        }

        $editForm = $this->createForm(new ChangementsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);


        //   return $this->render('ApplicationChangementsBundle:Changements:simple.html.twig', array());
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
        $session = $request->getSession();

        $path = $this->get('kernel')->getRootDir() . "/../web/uploads/documents/";

        // Flush in "safe" mode to enforce an Exception if keys are not unique

        if (!file_exists($path . $filename)) {
            $session->getFlashBag()->add('error', "Le fichier $filename n 'existe pas (code 1)");
            return $this->redirect($this->generateUrl('docchangements'));
        }

        try {
            $content = file_get_contents($path . $filename);
        } catch (\ErrorException $e) {
            $session->getFlashBag()->add('error', "Le fichier $filename n 'existe pas (code 2)");
            return $this->redirect($this->generateUrl('docchangements'));
        }


        //catches all exceptions extended from Exception (which is everything)



        /*  if (!$data = file_get_contents(file_get_contents($path.$filename))) {
          $session->getFlashBag()->add('error', "Le fichier $filename n 'existe pas: contacter l'administrateur");
          return $this->redirect($this->generateUrl('docchangements'));
          //  $content = file_get_contents($path.$filename);
          //if (!isset($content)){

          } */

        $response = new Response();

        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename);
        //$session = $this->getRequest()->getSession();
        $session->getFlashBag()->add('notice', "Le fichier $filename a ete téléchargé");

        $response->setContent($content);
        return $response;
    }

    public function TicketAjaxAction(Request $request) {
        $term = $request->get('term');

        $repository = $this->getDoctrine()->getRepository('MyAppMyBundle:City');
        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);

        $cities = $repository->searchCityByName($term);

        $json = array();

        foreach ($cities as $city) {
            $json[] = array(
                'label' => $city->getName() . ' (' . $city->getDepartement()->getNumber() . ')',
                'value' => $city->getName()
            );
        }

        $response = new Response();
        $response->setContent(json_encode($json));

        return $response;
    }

    public function ajaxCityAction(Request $request) {
        $value = $request->get('term');

        $em = $this->getDoctrine()->getEntityManager();
        $cities = $em->getRepository('GenemuEntityBundle:City')->findAjaxValue($value);

        $json = array();
        foreach ($cities as $city) {
            $json[] = array(
                'label' => $member->getName(),
                'value' => $member->getId()
            );
        }

        $response = new Response();
        $response->setContent(json_encode($json));

        return $response;
    }

    public function ajaxVilleAction(Request $request) {
        $value = $request->get('term');

        $em = $this->getDoctrine()->getEntityManager();
        $villes = $em->getRepository('RgbVilleBundle:Ville')->searchByNom($value);

        $json = array();
        foreach ($villes as $ville) {
            $json[] = array(
                'label' => $ville->getNom(),
                'value' => $ville->getId()
            );
        }

        $response = new Response();
        $response->setContent(json_encode($json));

        return $response;
    }

    public function listByProjetAction() {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $id = '';
            $applis = array();
            $cert_app = array();

            $id = $request->request->get('id_projet');
            $projet = $em->getRepository('ApplicationRelationsBundle:Projet')->find($id);

            $id_cert = $request->request->get('id_cert');
            if (isset($id_cert) && $id_cert != "create") {
                //    var_dump($id_cert);
                $cert = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id_cert);
                foreach ($cert->getIdapplis() as $appli) {
                    array_push($cert_app, $appli->getId());
                }
                $applis['cert'] = $cert_app;
            }
            foreach ($projet->getIdapplis() as $appli) {
                //$applis[] = array($appli);
                $applis['applis'][$appli->getId()] = $appli->getNomapplis();
                //      $applis[] = array($appli->getId(), $appli->getNomapplis());
            }

            //    $appli=array(3,4);
            $response = new Response(json_encode($applis));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        // return new Response();
    }

    public function ajaxprojetsAction() {
        $request = $this->get('request');

        if ($request->isXmlHttpRequest()) {
            $term = $request->request->get('motcle');

            $array = $this->getDoctrine()
                    ->getEntityManager()
                    ->getRepository('menCommandesBundle:commande')
                    ->listeNature($term);

            $response = new Response(json_encode($array));

            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    private function createCalendarForm($values = array()) {

        $myears = range(Date('Y') - 5, Date('Y') + 5);
        //$mmonth = range(1, 12);
        $mmonth = array(1 => 'jan', 2 => 'fev');
        // mmonth = array_map( sprintf("%02d",'floatval', $nonFloats);.
        //     sprintf("%02d",
        //    $myears = array("2012", "2013");
        // $mmonth = array("Janvier", "Fevrier");
        $year = isset($values['annee']) ? $values['annee'] : 'annee';
        $month = isset($values['mois']) ? $values['mois'] : 'mois';
        return $this->createFormBuilder()
                        /*    ->add('publishedAt', 'date', array(
                          'widget' => 'choice',
                          'empty_value' => array('year' => $year, 'month' => $month, 'day' => '1')
                          )) */
                        //   ->add('month', 'choice', array('label' => 'Mois', 'choices' => $mmonth,'data'=>$month))
                        //   ->add('year', 'choice', array('label' => 'Année', 'choices' => $myears, 'data' => $year))
                        ->add('publishedAt', 'birthday', array(
                            'widget' => 'choice',
                            'format' => 'yyyy-MM-dd',
                            'pattern' => '{{ year }}-{{ month }}-{{ day }}',
                            'years' => range(Date('Y'), 2008),
                            'label' => false,
                            'input' => 'string',
                                //    'data'=>'2013-05-01',
                                //   'data' => new \DateTime('2009-02-20')
                                //'data'  => date_create()
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
