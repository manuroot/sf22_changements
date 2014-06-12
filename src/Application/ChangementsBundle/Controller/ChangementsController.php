<?php

namespace Application\ChangementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\ChangementsBundle\Entity\Changements;
use Application\RelationsBundle\Entity\Document;
use Application\RelationsBundle\Entity\ChronoUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\ChangementsBundle\Form\ChangementsType;
use Application\ChangementsBundle\Form\ChangementsFilesForEntityType;
use Application\ChangementsBundle\Form\CalendarType;
use Application\ChangementsBundle\Form\ChangementsFilterType;
use Application\ChangementsBundle\Form\ChangementsStatusType;
use Application\ChangementsBundle\Form\ChangementsFilterAmoiType;
//use Application\ChangementsBundle\Entity\GridExport;
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
//use Doctrine\ORM\Tools\Pagination\CountOutputWalker;
//use Application\ChangementsBundle\Entity\ChangementsStatus;
use JMS\SecurityExtraBundle\Annotation\Secure;
//use Application\CentralBundle\Model\MesFiltresBundle;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/* use Pagerfanta\Pagerfanta;
  use Pagerfanta\Adapter\DoctrineORMAdapter;
  use Pagerfanta\Exception\NotValidCurrentPageException; */

/**
 * Changements controller.
 *
 */
class ChangementsController extends Controller {

    private function check_retour() {
        $session = $this->getRequest()->getSession();
        $btn_retour = $session->get('buttonretour');
        if ($btn_retour == 'changements_fanta' || $btn_retour == 'changements_myfanta')
            return $this->redirect($this->generateUrl($btn_retour));
        else
            return $this->redirect($this->generateUrl('changements_fanta'));
    }

    /* ====================================================================
     *
     * SECURITY
     *
      =================================================================== */

    private function getuserid() {

        //$em = $this->getDoctrine()->getManager();
        $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $user_id = $user->getId();
            /* $group = $user->getIdgroup();
              if (isset($group)) {
              $group_id = $group->getId();
              } else {
              $group_id = 0;
              } */
        } else {
            $user_id = 0;
            //  $group_id = 0;
        }
        return $user_id;
    }

    /* ====================================================================
     *
     * CREATION DU PAGINATOR
     *
      =================================================================== */

    private function createpaginator($query, $num_perpage = 5, $session_page = null) {

        $request = $this->getRequest();
        // $session = $request->getSession();
        $pagename = 'page'; // Set custom page variable name
        $page = $request->query->get($pagename, 1);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $page, $num_perpage, array(
            'pageParameterName' => $pagename,
            'distinct' => true,
            "sortDirectionParameterName" => "dir",
            'sortFieldParameterName' => "sort")
        );
        // $total=$pagination->getTotalItemCount();
        $pagination->setTemplate('ApplicationChangementsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        $pagination->setSortableTemplate('ApplicationChangementsBundle:pagination:sortable_link.html.twig');
        return $pagination;
    }

    /**
     * Create filter form and process filter request.
     *
     */
    protected function filter() {
        // $message = "filter datas";
        $message = "";
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new ChangementsFilterType());
        //createquerybuilder objet
        $filterBuilder = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindAll();
        // Reset filter
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "reset") {
            $session->remove('changementControllerFilter');
            $query = $filterBuilder;
            return array($filterForm, $query, $message);
        }
        // datas filter
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "filter") {
            $alldatas = $request->request->all();
            $datas = $alldatas["changements_filter"];
            $filterForm->bind($datas);
            if ($filterForm->isValid()) {
                $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $filterBuilder);
                $session->set('changementControllerFilter', $datas);
            } else {
                $query = $filterBuilder;
            }
            return array($filterForm, $query, $message);
        } else {
            // Get filter from session
            if ($session->has('changementControllerFilter')) {
                $datas = $session->get('changementControllerFilter');
                $filterForm->bind($datas);
                $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $filterBuilder);
            }
            // ou pas
            else {
                // $message = "pas de session";
                $query = $filterBuilder;
            }
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
        list($filterForm, $query, $message) = $this->filter();
        $queryBuilder = $query->getQuery();
        if ($message){
            $session->getFlashBag()->add('warning', "$message");
    }
        $pagination = $this->createpaginator($queryBuilder, 15);
        return $this->render('ApplicationChangementsBundle:Changements:indexpost.html.twig', array(
                    'search_form' => $filterForm->createView(),
                    'pagination' => $pagination,
                    'date_warning' => $date_warning,
        ));
    }

    public function indexposttestAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'changements_posttest');
        $datas = array();
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "reset") {
            $session->remove('changementControllerFilternew');
            /*   $session->remove('chgmts_page');
              $session->remove('chgmtsf_sort');
              $session->remove('chgmts_dir'); */
        } elseif ($request->getMethod() == 'POST' && $request->get('submit-filter') == "filter") {
            $alldatas = $request->request->all();
            $datas = $alldatas["changements_searchfilter"];
            $session->set('changementControllerFilternew', $datas);
            // $page=1;$dir='DESC';$sort='a.id';
        } else {
            if ($session->has('changementControllerFilternew')) {
                $datas = $session->get('changementControllerFilternew');
            }
        }
        // datas pour $em
        $searchForm = $this->createForm(new ChangementsFilterAmoiType($em, $datas));
        //$searchForm = $this->createForm(new ChangementsFilterAmoiType($em,$datas));
        $searchForm->bind($datas);
        $query = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindAll($datas);
        $query_changements = $query->getQuery();
        //$pagination = $this->createpaginator($query_changements, 20);
        $pagination = $this->createpaginator($query_changements, 20, 'page_chgmts_a');
        $count = $pagination->getTotalItemCount();
        return $this->render('ApplicationChangementsBundle:Changements:indexpostamoi.html.twig', array(
                    'search_form' => $searchForm->createView(),
                    'pagination' => $pagination,
                    'total' => $count,
        ));
    }

    /*
     * POST PARAMETERS
     * // obtenir respectivement des variables GET et POST
      $request->query->get('foo');

      $request->request->get('bar', 'valeur par défaut si bar est inexistant');

     */

    public function indexchartsAction() {

        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $myears = $em->getRepository('ApplicationChangementsBundle:Changements')->GetYears();
        $datas = array();
        // $myears = range(Date('Y') - 5, Date('Y') + 5);
        $current_date = new \DateTime();
        //$datas_session = $session->get('form_charts');
        $form = $this->smallCalendarForm($myears);
        if ($request->getMethod() == 'POST') {
            $dataform = $request->get('form');
            //   print_r($dataform);exit(1);
            $session->set('form_charts', $myears[$dataform['year']]);
            $year = $myears[$dataform['year']];
            $form->bind($dataform);
        }
        // pas de post
        else {

            $current_date = new \DateTime();
            $current_year = $current_date->format('Y');
            if (count($myears) > 0) {
                $key = array_search($current_year, $myears);
                if ($key) {
                    /*  echo "key=$key<br>";
                      exit(1); */
                    $form->bind(array('year' => $key));
                    //$form->bind(array('year'=> array_keys ($myears, $current_year)));
                    $year = $current_year;
                } else {
                    $year = $myears[0];
                }
            }
        }

        // $form->bind($datas);
        // echo "year=$year<br>";
        // exit(1);
        $titre = "Demandes " . $year;
        $all_months = $em->getRepository('ApplicationChangementsBundle:Changements')->get_all_months();
        $data_sumbymonth = $em->getRepository('ApplicationChangementsBundle:Changements')->sum_allappli_bymonthyear($year);
        $data_month = $em->getRepository('ApplicationChangementsBundle:Changements')->sum_appli_monthyear($year);


        $pie_options = array(
            'allowPointSelect' => true,
            'cursor' => 'pointer',
            'dataLabels' => array(
                'enabled' => true,
                'color' => '#000000',
                'connectorColor' => '#000000',
                'format' => '{point.name}'
            /* 'format'=> '<b>{point.name}</b>: {point.percentage:.1f} %' */
            )
        );
        $res2 = $data_sumbymonth->getScalarResult();
        $hbar_parmois = $data_month->getScalarResult();
        // var_dump($res2);exit(1);
        $series_bymois = array();
        foreach ($res2 as $k => $v) {
            $series_bymois[(integer) ($v['mois']) - 1] = (integer) $v['nb'];
        }
        for ($i = 0; $i < 12; $i++) {
            if (!isset($series_bymois[$i]))
                $series_bymois[$i] = null;
        }

        ksort($series_bymois);



        $series = array(
            // array("name" => "Data Serie Name", "data" => array(1, 2, 4, 7, 6,9))
            array("name" => "operations", "data" => $series_bymois)
        );

        $applis = array();
        foreach ($hbar_parmois as $k => $v) {
            $applis[$v["projet"]][(integer) $v['mois'] - 1] = (integer) $v['nb'];
        }
        for ($i = 0; $i < 12; $i++) {
            foreach ($applis as $k => $v) {
                if (!isset($v[$i]))
                    $applis[$k][$i] = null;
            }
        }
        foreach ($hbar_parmois as $k => $v) {
            ksort($applis[$v["projet"]]);
        }
        $series4 = array();
        foreach ($applis as $k => $v) {
            array_push($series4, array(
                "name" => $k,
                "data" => $v
            ));
        }
        // var_dump($applis);
        // exit(1);

        $ob1 = new Highchart();
        $ob1->chart->renderTo('linechart1'); // The #id of the div where to render the chart
        $ob1->chart->type('column');
        $ob1->title->text('Demandes ' . $year);
//$categories = array('Jan', 'Feb', 'Mar', 'Apr', 'May');
        $categories = $all_months;
        $ob1->xAxis->categories($categories);
        $ob1->xAxis->title(array('text' => "Année:" . $year . " Répartition mensuelle"));
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

        $ob1->series($series);

        //=====================================================
        // Par Projet
        //=====================================================
        $data = $em->getRepository('ApplicationChangementsBundle:Changements')->sum_appli_year($year);
        // print_r($data);exit(1);
        $ob2 = new Highchart();
        $ob2->chart->type('column');

        $ob2->chart->plotBackgroundColor(null);
        $ob2->chart->plotBorderWidth(false);
        $ob2->chart->plotShadow(false);
        $ob2->chart->renderTo('linechart2');
        $ob2->title->text($titre . ': Projets');
        // $ob2->tooltip(array( 'pointFormat'=> '{series.name}: <b>{point.percentage:.1f}%</b>'));
        $ob2->tooltip->pointFormat('{series.name}: <b>{point.percentage:.1f}%</b>');

        $ob2->plotOptions->pie($pie_options);

        $ob2->series(array(array('type' => 'pie', 'name' => 'Demandes', 'data' => $data)));

        //=====================================================
        // Par demandeur
        //=====================================================
        $data_demandeur = $em->getRepository('ApplicationChangementsBundle:Changements')->sum_demandeur_year($year);
        $ob5 = new Highchart();
        $ob5->chart->renderTo('linechart5');
        $ob5->title->text($titre . ': Demandeurs');
        $ob5->tooltip->pointFormat('{series.name}: <b>{point.percentage:.1f}%</b>');
        $ob5->plotOptions->pie($pie_options);
        $ob5->series(array(array('type' => 'pie', 'name' => 'Browser share', 'data' => $data_demandeur)));

        //=====================================================
        // Par Group sur une année
        //=====================================================
        $data_groupe = $em->getRepository('ApplicationChangementsBundle:Changements')->sum_group_year($year);
        $ob6 = new Highchart();
        $ob6->chart->renderTo('linechart6');
        $ob6->title->text($titre . ': Services');
        $ob6->tooltip->pointFormat('{series.name}: <b>{point.percentage:.1f}%</b>');
        $ob6->plotOptions->pie($pie_options);
        $ob6->series(array(array('type' => 'pie', 'name' => 'Browser share', 'data' => $data_groupe)));

        //=====================================================
        // Par Projet et par mois sur une année
        //=====================================================
        $ob4 = new Highchart();
        $ob4->chart->renderTo('linechart4');
        $ob4->chart->type('column');
        $ob4->plotOptions->column(array(
            'stacking' => 'stacked',
            /* 'stacking' => 'percent', */
            'dataLabels' => array(
                'enabled' => true,
            ),
        ));

        // $ob4->plotOptions->series(array('stacking'=> 'normal'));
        $ob4->title->text($titre . ': Projets par mois');
        $ob4->legend->backgroundColor('#FFFCCE');
        $ob4->legend->reverse(true);
        //'reversed'=> true
        //));

        $ob4->xAxis->categories($all_months);
        $ob4->yAxis->min(0);
        // $ob4->yAxis->max(5);
        $ob4->yAxis->title(array('text' => 'Total Applications'));
        $ob4->yAxis->stackLabels(array('enabled' => true,
            'style' => array('fontWeight' => 'bold',
                'color' => 'Highcharts.theme && Highcharts.theme.textColor) || "gray"')
        ));
        //$series4 = $res;
        $ob4->series($series4);


        return $this->render('ApplicationChangementsBundle:Changements:indexcharts.html.twig', array(
                    'chart1' => $ob1,
                    'chart2' => $ob2,
                    'chart5' => $ob5,
                    'chart4' => $ob4,
                    'chart6' => $ob6,
                    'form' => $form->createView(),
                    'year' => $year,
        ));
    }

    public function indexdashboardAction() {


        return $this->render('ApplicationChangementsBundle:Changements:indexdashboard.html.twig', array(
        ));
    }

    //==============================================
    // VIEW CALENDRIER
    //==============================================

    public function calendarAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $parameters = array();
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'changements_calendar');
        $datas_session = $session->get('calendar_dates');
        $form = $this->createForm(new CalendarType($em));
        if ($request->getMethod() == 'POST') {

            $dataform = $request->get('changements_calendar_form');
            $parameters = $dataform;


            $session->set('calendar_dates', $dataform);
            $current_year = $dataform['publishedAt']['year'];
            $current_month = $dataform['publishedAt']['month'];
            if ($request->get('submit') == "next") {
                if ($current_month == 12) {
                    $current_month = 1;
                    $current_year+=1;
                } else {
                    $current_month +=1;
                }
                $dataform['publishedAt']['year'] = $current_year;
                $dataform['publishedAt']['month'] = $current_month;
            } elseif ($request->get('submit') == "previous") {
                if ($current_month == 1) {
                    $current_month = 12;
                    $current_year-=1;
                } else {
                    $current_month = $current_month - 1;
                }
                $dataform['publishedAt']['year'] = $current_year;
                $dataform['publishedAt']['month'] = $current_month;
            }
            //  print_r($dataform);exit(1);
            $form->bind($dataform);
        } elseif (isset($datas_session)) {
            // echo "data set<br>";
            // exit(1);
            $datas = $session->get('calendar_dates');
            $current_year = $datas['publishedAt']['year'];
            $current_month = $datas['publishedAt']['month'];
            $form->bind($datas);
        }
        // pas de sesion
        else {
            // echo "pas de session<br>";
            //print_r($datas_session);
            // exit(1);
            $current_date = new \DateTime();
            // $next=
            //$current_yearmonth = $current_date->format('Y-m');
            $current_year = $current_date->format('Y');
            $current_month = $current_date->format('m');
            $datas = array();
            $datas['publishedAt'] = array(
                'year' => (int) $current_year, 'month' => (int) $current_month, 'day' => 1,
            );
            $form->bind($datas);
        }

        $month = $this->get('calendr')->getMonth($current_year, $current_month);
        $eventCollection = $this->get('calendr')->getEvents($month, $parameters);
        return $this->render('ApplicationChangementsBundle:Changements:calendar.html.twig', array(
                    'month' => $month,
                    'evenement' => $eventCollection,
                    'form' => $form->createView(),
                    'year' => $current_year,
                        // 'current_month' => $month
        ));
    }

    //==============================================
    // VIEW APY
    //==============================================
    public function indexapyAction($page = 1) {

        $session = $this->getRequest()->getSession();
        // ajoute des messages flash
        $session->set('buttonretour', 'changements_apy');
        $source = new Entity('ApplicationChangementsBundle:Changements');
        $source->manipulateRow(
                function ($row) {
            $currenta = $row->getField('idStatus.nom');
            if ($currenta == 'open') {
                $row->setColor('#dff0d8;');
            } elseif ($currenta == 'en preparation') {
                $row->setColor('#EDF3FE');
            }
            return $row;
        }
        );
        $grid = $this->container->get('grid');
        // Attach the source to the grid
        $grid->setSource($source);

        $grid->setId('changementsgrid');
        $grid->addExport(new CSVExport('CSV Export', 'Operations', array('delimiter' => ';'), 'Windows-1252'));
        $grid->addExport(new ExcelExport('Excel Export', 'Operations', array('delimiter' => ';'), 'Windows-1252'));

        $grid->setPersistence(false);
        $grid->setDefaultOrder('id', 'desc');
        // Set the selector of the number of items per page
        $grid->setLimits(array(50));

      /* $grid->setDefaultFilters(array(
    
    'id' => array('isNull' => 'your_init_value1'), // Use the default operator of the column
    
    ));*/

        // Set the default page
        $grid->setPage($page);
        $grid->addMassAction(new DeleteMassAction());
        $grid->setActionsColumnSize(70);
        // $grid->setDefaultFilters(array('idEnvironnement.nom:AtGroupConcat' => array('operator' => 'like')));
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
        $entity = $this->get('changement.common.manager')->checkandloadChangement($id);
        foreach ($entity->getIdusers() as $u) {
            /* echo "id=" . $u->getId() . "-- ";
              echo "mail=--" . (string) $u->getNomUser() . "-- <br>";
              var_dump($u->getTelephone());
              var_dump($u->getInfos());
              var_dump($u->getBureau());
              echo "mail=--" .  $u->getTelephone() . "-- <br>";
             */
        }
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('ApplicationChangementsBundle:Changements:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    public function showXhtmlAction($id) {
        $entity = $this->get('changement.common.manager')->loadChangement($id);
        $deleteForm = $this->createDeleteForm($id);
     /*      $request = $this->getRequest();
        $session = $request->getSession();
    */
    //        $session = $this->getRequest()->getSession();
      
            
        return $this->render('ApplicationChangementsBundle:Changements:showxhtml.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }
    
    public function showXhtmlFichiersAction($id) {
        $entity = $this->get('changement.common.manager')->loadChangement($id);
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('ApplicationChangementsBundle:Changements:showxhtml.html.twig', array(
                    'entity' => $entity,
                    'active_file' => 'files',
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new Changements entity.
     *
     * @Secure(roles="ROLE_USER")
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

    /**
     * Displays a form to create a new Changements entity.
     *
     * @Secure(roles="ROLE_USER")
     */
    public function newcloneAction($id) {
        $entity = $this->get('changement.common.manager')->loadChangement($id);
        $copy = clone $entity;
        /* $em = $this->getDoctrine()->getManager();
          $em->persist($copy);
          $em->flush(); */
        $copy->setDateDebut(null);
        $copy->setDateFin(null);
        $copy->setIdStatus(null);
        $copy->setTicketExt(null);
        $copy->setTicketInt(null);


        $id = $copy->getId();
        $editForm = $this->createForm(new ChangementsType(), $copy);
        $deleteForm = $this->createDeleteForm($id);
        //   return $this->get('router')->generate('changements_update', array('id' => $id));
        //  return $this->redirect($this->generateUrl('changements_edit', array('id' => $id)));
        /* return $this->render('ApplicationChangementsBundle:Changements:edit.html.twig', array(
          'entity' => $copy,
          //  'form' => $editForm->createView(),
          'edit_form' => $editForm->createView(),
          'delete_form' => $deleteForm->createView(),
          'is_clone' => 'yes'
          )); */

        return $this->render('ApplicationChangementsBundle:Changements:new.html.twig', array(
                    'entity' => $copy,
                    //  'form' => $editForm->createView(),
                    'form' => $editForm->createView(),
                    //'delete_form' => $deleteForm->createView(),
                    'is_clone' => 'yes'
        ));
    }

    /**
     * Displays a form to create a new Changements entity.
     *
     * @Secure(roles="ROLE_USER")
     */
    public function newflowstartAction() {
        // form data class
        // $entity = new Changements();
        $flow = $this->get('application.form.flow.new.changement');
        $flow->reset();
        return $this->redirect($this->generateUrl('changements_newflow'));
    }

    /**
     * Displays a form to create a new Changements entity.
     *
     */

    /**
     * Displays a form to create a new Changements entity.
     *
     * @Secure(roles="ROLE_USER")
     */
    public function newflowAction() {
        // form data class
        $entity = new Changements();
        $status = "ok";
        $flow = $this->get('application.form.flow.new.changement');
        // $flow->reset();
        $flow->bind($entity);
        // form of the current step
        $form = $flow->createForm();
        // $form = $flow->createForm($entity);
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
                return $this->check_retour();
            }
        } else {
            $status = "NOK";
        }

        return $this->render('ApplicationChangementsBundle:Changements:newflow.html.twig', array(
                    'form' => $form->createView(),
                    'flow' => $flow,
                    'entity' => $entity,
                    'status' => $status,
        ));
    }

    protected function testy() {



        $em = $this->getDoctrine()->getManager();
        $entity_c = $this->get('changement.common.manager')->loadChangement(580);
        //  $entity_c = $em->getRepository('ApplicationChangementsBundle:Changements')->find(580);
        echo "<br>11=";
        foreach ($entity_c->getIdusers() as $u) {

            if (!$u) {
                throw $this->createNotFoundException('Unable to find ChronoUser entity.');
            }
            echo $u->getId() . "--" . $u->getInfos() . $u->getEmail();
        }
    }

    public function xsendemailAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('changement.common.manager')->mygetusers($id);

        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find("580");


        $user_id = $this->getuserid();
        if (!isset($user_id)) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        }
        $entity_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);


        $demandeur = $entity_user->getUsername();
        echo "demandeur=$demandeur";
        //  $users = $entity->getIdusers();
        $title = $entity->getNom();
        $subject = "Création d'une demande de changement" . ": " . $title;
        echo "subject=$subject";
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($demandeur . '@pc-supervision.fr');
        echo "<br>";
        foreach ($entity->getIdEnvironnement() as $u) {

            //  echo "id=--" . (string) $u->getEmail() . "-- <br>";
            echo "id=--" . $u->getDescription() . "-- <br>";
        }
        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find("580");

        foreach ($entity->getIdUsers() as $u) {

            //  echo "id=--" . (string) $u->getEmail() . "-- <br>";
            echo "id=--" . $u->getEmail() . "-- <br>";
        }



        $message->setBody(
                $this->renderView(
                        'ApplicationChangementsBundle:Changements:email.txt.twig', array(
                    'entity' => $entity)
        ));
        //  return new Response($message);
        //   var_dump($message);
        //   exit(1);
        // $this->get('mailer')->send($message); 
        return $message;
    }

    /**
     * Creates a new Changements entity.
     *
     * @Secure(roles="ROLE_USER")
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
          //  echo "here";exit(1);
              $id = $entity->getId();
            $session = $request->getSession();
            
            $session->getFlashBag()->add('warning', "Enregistrement $id ajouté aux opérations");
            // ajoute des messages flash
           /*
             $session->getFlashBag()->add('notice', 'Email envoyé!');
            
            $manager = $this->get('changement.common.manager');
            $email_state = $this->container->getParameter('application_changements.email_state');
            $email_to = $this->container->getParameter('application_changements.email_to');
            
           $mess = $manager->sendemailChangement($id, $email_state, $email_to);
            $mess->setBody(
                    $this->renderView(
                            'ApplicationChangementsBundle:Changements:email.html.twig', array(
                        'message' => 'AJOUT:',
                        'entity' => $entity)
                    ), 'text/html');
            $this->get('mailer')->send($mess);
            * */
           
            return $this->check_retour();
        }
        return $this->render('ApplicationChangementsBundle:Changements:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Changements entity.
     *
     * 
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     *
     */
    public function editAction($id) {

        $entity = $this->get('changement.common.manager')->loadChangement($id);
        $editForm = $this->createForm(new ChangementsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        //return $this->check_retour();
        return $this->render('ApplicationChangementsBundle:Changements:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * 
     *
     * @Secure(roles="ROLE_USER")
     */
    public function newFichierAction($id) {

        $entity = $this->get('changement.common.manager')->loadChangement($id);
        $editForm = $this->createForm(new ChangementsType(), $entity);
        return $this->render('ApplicationChangementsBundle:Changements:new_fichier.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Edits an existing Changements entity.
     *
     * @Secure(roles="ROLE_USER")
     */
    public function updateAction(Request $request, $id) {

        $manager = $this->get('changement.common.manager');
        $entity = $manager->checkandloadChangement($id);
        //??
        //$entity = $manager->loadChangement($id);
        $editForm = $this->createForm(new ChangementsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $manager->saveChangement($entity);
            /* pas d'envoi a l'update*/
            /*
            $email_state = $this->container->getParameter('application_changements.email_state');
            $email_to = $this->container->getParameter('application_changements.email_to');
            $mess = $manager->sendemailChangement($id, $email_state, $email_to);

            $mess->setBody(
                    $this->renderView(
                            'ApplicationChangementsBundle:Changements:email.html.twig', array(
                        'message' => 'UPDATE:',
                        'entity' => $entity)
                    ), 'text/html');
            */
            //$message->setContentType("text/html")

            /*   $em = $this->getDoctrine()->getManager();
              $entity_c = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);
             */
            // var_dump($mess->getFrom());
            //exit(1);
           // $this->get('mailer')->send($mess);
            
            $session = $this->getRequest()->getSession();


            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");
        //    $session->getFlashBag()->add('notice', "id=$id: Email envoyé");
            return $this->check_retour();
        }

        $session = $request->getSession();
        $session->getFlashBag()->add('warning', "Form non valide");

        return $this->render('ApplicationChangementsBundle:Changements:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

//updateentityfiles
    /**
     * Deletes a Changements entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $this->get('changement.common.manager')->deleteChangement($id);
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
                $fic = $document->generateFilename();
                echo "$fic<br>";
                exit(1);
                // $document->upload();
                $em->persist($document);
                $em->flush();
            }
        }
        return $this->render('ApplicationChangementsBundle:Changements:upload.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    private function smallCalendarForm($myears = null) {

        if (!isset($myears)) {
            $current_date = new \DateTime();
            $myears = $current_date->format('Y');
        }
        return $this->createFormBuilder()
                        ->add('year', 'choice', array(
                            'label' => false,
                            'choices' => $myears,
                                // 'data' => 2013,
                        ))
                        //  ->add('Valider', 'submit')
                        ->getForm();
    }

    //==============================================
    //          REQUETES AJAX
    // 
    //==============================================
    /**
     *
     * @Secure(roles="ROLE_USER")
     */
    public function update_changement_statusAction() {
        $request = $this->get('request');

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();

            $id = $request->request->get('id');
            $entity = $this->get('changement.common.manager')->checkandloadChangement($id);
            // return le nom du status
            $id_status = $entity->getIdStatus();
            $new_status = $id_status;
            if ($id_status == "open") {
                $entity_status = $em->getRepository('ApplicationChangementsBundle:ChangementsStatus')->findOneByDescription("closed");
                // var_dump($id_status);

                $entity->setIdStatus($entity_status);
                $new_status = "closed";
                $em->persist($entity);
                $em->flush();
            }
            if ($id_status == "en preparation" || $id_status == "en attente") {
                $entity_status = $em->getRepository('ApplicationChangementsBundle:ChangementsStatus')->findOneByDescription("open");
                // var_dump($id_status);
                $new_status = "open";
                $entity->setIdStatus($entity_status);
                $em->persist($entity);
                $em->flush();
            } elseif ($id_status == "closed") {
                $entity_status = $em->getRepository('ApplicationChangementsBundle:ChangementsStatus')->findOneByDescription("prepare");
                $entity->setIdStatus($entity_status);
                $new_status = "prepare";
                $em->persist($entity);
                $em->flush();
            }
            $array = array('mystatus' => "$id_status==>$new_status");
            //  $array = array($array);
            $response = new Response(json_encode($array));

            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    //==============================================
    //          REQUETES AJAX
    // 
    //        A TESTER: $user_security->setToken(null);  ???
    //==============================================
    public function checkuserAction() {
        $request = $this->get('request');

        $from = $request->request->get('from');
        $array['status'] = true;
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $user_security = $this->container->get('security.context');
            if (!$user_security->isGranted('IS_AUTHENTICATED_FULLY') && isset($from)) {
                $array['status'] = false;
            } else {
                // 1er passage
                $session = $request->getSession();
                if (!$session->has('inactivity')) {
                    $session->set('inactivity', time());
                }
                //$session_data = $session->getMetadataBag();
                // Expire sessions if unused for $idletimeout
                $idle_timeout = $this->container->getParameter('application_changements.session_timeout');
                $idle = time() - $session->get('inactivity');
                $array['idle'] = $idle;
                if ($idle > $idle_timeout) {
                    $session->invalidate();
                    //     $user_security->setToken(null); 
                    //$session->invalidate();
                    $array['status'] = false;
                }
            }
            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } elseif ($request->isXmlHttpRequest() && $request->getMethod() == 'GET') {

            $response = new Response();
            //  return new Response('<html><body>Hello '.$name.'!</body></html>');
            $response->setContent("OK");
            return $response;
//echo "OK";exit(1);
        }
    }

    //==============================================
    //          REQUETES AJAX
    // 
    //==============================================
    public function update_changement_favorisAction() {
        $request = $this->get('request');

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $id = $request->request->get('id');

            $user_security = $this->container->get('security.context');
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            if (!$user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
                
            }
            /*
             * 
             * http://forum.symfony-project.org/viewtopic.php?t=36827&p=125930
             * http://stackoverflow.com/questions/10846970/catch-session-timeout-symfony2
              }          *
             * http://stackoverflow.com/questions/18872721/symfony2-security-automatic-logout-after-an-inactive-period
              http://symfony-gu.ru/documentation/en/html/components/http_foundation/session_configuration.html    http://www.paulirish.com/2009/jquery-idletimer-plugin/
             * 
             */

            $user_id = $this->getuserid();
            if (!isset($user_id)) {
                $array['mystatus'] = "false";
                throw $this->createNotFoundException('Unable to find Changements entity.');
                $response = new Response(json_encode($array));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
                // echo "user_id=$user_id";
            }

            $em = $this->getDoctrine()->getManager();
            $entity = $this->get('changement.common.manager')->checkandloadChangement($id);
            $entity_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
            /* if (!isset($entity_user)){
              $array['mystatus'] = "false";
              throw $this->createNotFoundException('Unable to find Changements entity.');
              $response = new Response(json_encode($array));
              $response->headers->set('Content-Type', 'application/json');
              return $response;

              } */
            $stat = $entity->checkIdfavoris($entity_user);
            // si true==> present
            if ($stat == true) {
                $entity->removeIdfavoris($entity_user);
                $array['mystatus'] = "removed";
            } else {

                //si false absent
                $entity->addIfavoris($entity_user);
                $array['mystatus'] = "added";
            }
            $em->persist($entity);
            $em->flush();

            $response = new Response(json_encode($array));

            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    //==============================================
    // AJAX
    //==============================================

    public function GetTicketExtAjaxAction($field, $term) {

        $em = $this->getDoctrine()->getManager();
        $entity_ticket = $em->getRepository('ApplicationChangementsBundle:Changements')->findAjaxValue(array($field => $term));
        $json = array();
        foreach ($entity_ticket->getQuery()->getResult() as $ticket) {
            array_push($json, $ticket->getTicketExt());
        }
        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    //==============================================
    //          REQUETES AJAX
    // 
    //==============================================

    public function DescriptionAjaxAction(Request $request) {
        $term = $request->get('term');
        $em = $this->getDoctrine()->getManager();
        $entity_ticket = $em->getRepository('ApplicationChangementsBundle:Changements')->findAjaxValue(array('description' => $term));
        $json = array();
        foreach ($entity_ticket->getQuery()->getResult() as $ticket) {
            array_push($json, $ticket->getDescription());
        }
        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    //==============================================
    //          REQUETES AJAX
    // 
    //==============================================

    public function NomAjaxAction(Request $request) {
        $term = $request->get('term');
        $em = $this->getDoctrine()->getManager();
        $entity_ticket = $em->getRepository('ApplicationChangementsBundle:Changements')->findAjaxValue(array('nom' => $term));
        $json = array();
        foreach ($entity_ticket->getQuery()->getResult() as $ticket) {
            if (!in_array((string) $ticket->getNom(), $json))
                array_push($json, (string) $ticket->getNom());
        }
        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    //==============================================
    //          REQUETES AJAX
    // 
    //==============================================

    public function TicketExtAjaxAction(Request $request) {
        $term = $request->get('term');
        $em = $this->getDoctrine()->getManager();
        $entity_ticket = $em->getRepository('ApplicationChangementsBundle:Changements')->findAjaxValue(array('ticketExt' => $term));
        $json = array();
        foreach ($entity_ticket->getQuery()->getResult() as $ticket) {
            if (!in_array((string) $ticket->getTicketExt(), $json))
                array_push($json, (string) $ticket->getTicketExt());
        }
        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
      //==============================================
    //          REQUETES AJAX
    // 
    //==============================================

    public function TicketPbmAjaxAction(Request $request) {
        $term = $request->get('term');
        $em = $this->getDoctrine()->getManager();
        $entity_ticket = $em->getRepository('ApplicationChangementsBundle:Changements')->findAjaxValue(array('ticketPbm' => $term));
        $json = array();
        foreach ($entity_ticket->getQuery()->getResult() as $ticket) {
            if (!in_array((string) $ticket->getTicketPbm(), $json))
                array_push($json, (string) $ticket->getTicketPbm());
        }
        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    //==============================================
    //          REQUETES AJAX
    // 
    //==============================================

    public function TicketIntAjaxAction(Request $request) {
        $term = $request->get('term');
        $em = $this->getDoctrine()->getManager();
        $entity_ticket = $em->getRepository('ApplicationChangementsBundle:Changements')->findAjaxValue(array('ticketInt' => $term));
        $json = array();
        foreach ($entity_ticket->getQuery()->getResult() as $ticket) {
            if (!in_array((string) $ticket->getTicketInt(), $json))
                array_push($json, (string) $ticket->getTicketInt());
        }
        // exit(1);
        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    //==============================================
    //          REQUETES AJAX
    // 
    //==============================================

    public function listByProjetAction() {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();

            $applis = array();
            $applis['chgmnt'] = array();
            $changements_app = array();

            $id = $request->request->get('id_projet', null);
            // recup ts les projets
            if (isset($id) && $id != "") {
                $projet = $em->getRepository('ApplicationRelationsBundle:Projet')->find($id);
                // 
                $id_changement = $request->request->get('id_changement');
                if (isset($id_changement) && $id_changement != "") {

                    // var_dump($id_cert);
                    //recup du changement avec applis associées:
                    $changement = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id_changement);
                    foreach ($changement->getIdapplis() as $appli) {
                        array_push($changements_app, $appli->getId());
                    }
                    $applis['chgmnt'] = $changements_app;
                }

                // recup toutes les applis associées au projet selectionné:
                foreach ($projet->getIdapplis() as $appli) {
                    //$applis[] = array($appli);
                    $applis['applis'][$appli->getId()] = $appli->getNomapplis();
                    // $applis[] = array($appli->getId(), $appli->getNomapplis());
                }
            }
            // $appli=array(3,4);
            $response = new Response(json_encode($applis));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        // return new Response();
    }

    //==============================================
    //          REQUETES AJAX
    // 
    //==============================================

    public function ajaxprojetsAction() {
        $request = $this->get('request');

        if ($request->isXmlHttpRequest()) {
            $term = $request->request->get('motcle');

            $array = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('menCommandesBundle:commande')
                    ->listeNature($term);

            $response = new Response(json_encode($array));

            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    public function indexposttestdebugAction(Request $request) {

        $parameters = array();
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();

        $sort = $this->get('request')->query->get('sort');
        $query = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindAll();
        $query_changements = $query->getQuery();
        // avec query->getQuery() only
        $query_changements->setFirstResult(0);
        $query_changements->setMaxResults(10);
        $count = 10; //for test
        $pagination = $this->createpaginator($query_changements, 10);
        return $this->render('ApplicationChangementsBundle:Changements:indexpostamoi_debug.html.twig', array(
                    'pagination' => $pagination,
                    'total' => $count,
        ));
    }

    private function mypager($adapter = null, $max = 5, $page = 1) {
        if (isset($adapter)) {
            $pagerfanta = new Pagerfanta($adapter);
            $pagerfanta->setMaxPerPage($max);

            return $pagerfanta;
        } else {
            return null;
        }
    }

    public function templates2Action() {
        return $this->render('ApplicationChangementsBundle:templates:theme2.html.twig', array(
        ));
    }

    public function templates1Action() {
        return $this->render('ApplicationChangementsBundle:templates:theme1.html.twig', array(
        ));
    }

    //==============================================
    //          ORDER
    // 
    //==============================================

    private function OrderfantaAction() {

        $request = $this->getRequest();
        $session = $request->getSession();
        //------------------------------------------
        // PAGE SESSION
        //------------------------------------------
        $page = $request->query->get('page');
        /* if (! preg_match("/[0-9]+/", $page)) 
          $page=1; */
        if (isset($page) && preg_match("/^[0-9]+$/", $page)) {
            $session->set('chgmtsfanta_page', $page);
            //exit(1);
        } elseif ($session->has('chgmtsfanta_page')) {
            $page = $session->get('chgmtsfanta_page');
        } else {
            $page = 1;
        }

        //------------------------------------------
        // ORDER SORT SESSION
        //------------------------------------------
        // ajouter session + masquer parametres
        $sort = $this->get('request')->query->get('sort');
        if (isset($sort)) {
            $session->set('chgmtsfanta_sort', $sort);
            //exit(1);
        } elseif ($session->has('chgmtsfanta_sort')) {
            $sort = $session->get('chgmtsfanta_sort');
        } else {
            $sort = 'a.id';
        }

        //------------------------------------------
        // ORDER SORT SESSION
        //------------------------------------------

        $dir = $this->get('request')->query->get('dir');
        if (isset($dir)) {
            $session->set('chgmtsfanta_dir', $dir);
            //exit(1);
        } elseif ($session->has('chgmtsfanta_dir')) {
            $dir = $session->get('chgmtsfanta_dir');
        } else {
            $dir = 'DESC';
        }


        return array($page, $dir, $sort);
    }

    //==============================================
    //          INDEX FAVORIS
    // 
    //==============================================

    public function indexfantaAction(Request $request) {

        $parameters = array();
        $em = $this->getDoctrine()->getManager();
        // Pour les favoris
        $user_id = $this->getuserid();
        $changements_nb_status = $em->getRepository('ApplicationChangementsBundle:Changements')->getStatusForRequeteBuilder();
        $request = $this->getRequest();
        //$request = $this->container->get('request');
        $session = $request->getSession();
        $session->set('buttonretour', 'changements_fanta');
        $searchForm = $this->createForm(new ChangementsFilterAmoiType($em));
        $statusForm = $this->createForm(new ChangementsStatusType());
        //-----------------------------------------
        // On efface les sessions si post 
        //------------------------------------------
        if ($request->getMethod() == 'POST') {
            $session->remove('chgmtsfanta_page');
            $session->remove('chgmtsfanta_sort');
            $session->remove('chgmtsfanta_dir');
            $session->remove('changementControllerFilternew');
            if ($request->get('submit-filter') == "reset") {
                $session->getFlashBag()->add('warning', "Filtres de recherche reinitialisés");
                $session->remove('date_calendar');
            }
            //-----------------------------------------
            // On recupere les vars de post ==> session filter
            //------------------------------------------
            elseif ($request->get('submit-filter') == "filter") {
                $session->remove('changementControllerFilternew');
                $alldatas = $request->request->all();
                $datas = $alldatas["changements_searchfilter"];
               //     echo "submit filter<br"; print_r($datas);exit(1);
                $parameters = $datas;
                $session->set('changementControllerFilternew', $datas);
                $searchForm->bind($datas);
            }
            $page = 1;
            $dir = 'DESC';
            $sort = 'a.id';
        }
        //-----------------------------------------
        // On recupere les vars de session filter
        //------------------------------------------
        elseif ($session->has('changementControllerFilternew')) {
            $datas = $session->get('changementControllerFilternew');
            $parameters = $datas;
            $searchForm->bind($datas);
            // $page=1;$dir='DESC';$sort='a.id';
            list($page, $dir, $sort) = $this->OrderfantaAction();
        } else {
            list($page, $dir, $sort) = $this->OrderfantaAction();
            //$page=1;$dir='DESC';$sort='a.id';
        }

        if (!$page) {
            $page = 1;
        }
        //-----------------------------------------
        // On recupere les vars de session page,dir,order
        //------------------------------------------
        //list($page, $dir, $sort) = $this->OrderfantaAction();
        $next_dir = ($dir == 'DESC') ? 'ASC' : 'DESC';
        $arrow[$sort] = $next_dir == "DESC" ? 'icon-arrow-up' : 'icon-arrow-down';
        //$routeName = $request->get('_route');
        //echo "route=$routeName";exit(1);
        // if ($routeName != "changements_fanta")
        //     $parameters['operation']=1;

        $query = $em->getRepository('ApplicationChangementsBundle:Changements')->getJoinedBy($sort, $dir, $parameters);

        $adapter = new DoctrineORMAdapter($query);
        //$adapter->setDistinct(false);
        // sur changement categories avec filtres la page n'est peut etre
        // plus dispo (avec les sessions) !!!!!!!!!!
        // A debugger
        $pagerfanta = $this->mypager($adapter, 20);
        $nb_pages = $pagerfanta->getNbPages();
        if ($page > $nb_pages) {
            $session = $this->getRequest()->getSession();
            //$session->getFlashBag()->add('warning', "Enregistrement  update successfull");
            $session->getFlashBag()->add('warning', "Page $page n'exite pas: goto Page1");
            $page = 1;
        }

        try {
            $pagerfanta->setCurrentPage($page);
            $nbResults = $pagerfanta->getNbResults();
            // $nbResults=317;
            $q = $pagerfanta->getCurrentPageResults();
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
            // throw $this->createNotFoundException('Unable to find entity.');
        }
        //if ($routeName != "changements_fanta")
        //   $currenttwig='indexfanta.html.twig';
        // else 
        //      $currenttwig='indexfanta.html.twig';
        /*return $this->render('ApplicationChangementsBundle:Changements:indexfanta_bs3.html.twig', array(*/
        return $this->render('ApplicationChangementsBundle:Changements:indexfanta.html.twig', array(
                    'pagerfanta' => $pagerfanta,
                    'entities' => $q,
                    'next_dir' => $next_dir,
                    'search_form' => $searchForm->createView(),
                    'status_form' => $statusForm->createView(),
                    'arrow' => $arrow,
                    'nb_pages' => $nb_pages,
                    'nbResults' => $nbResults,
                    'user_id' => $user_id,
                    'nb_status' => $changements_nb_status
        ));
    }

    //==============================================
    //          INDEX FAVORIS
    // 
    //==============================================

    /**
     * Displays a form to edit an existing Changements entity.
     *
     * 
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     *
     */
    public function indexmyfantaAction(Request $request) {

        $parameters = array();
        $em = $this->getDoctrine()->getManager();
        $changements_nb_status = $em->getRepository('ApplicationChangementsBundle:Changements')->getStatusForRequeteBuilder();
      
        // Pour les favoris
        // echo "user_id=$user_id";
        //  exit(1);
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'changements_myfanta');
        $searchForm = $this->createForm(new ChangementsFilterAmoiType($em));
        $statusForm = $this->createForm(new ChangementsStatusType());
        //-----------------------------------------
        // On efface les sessions si post 
        //------------------------------------------
        if ($request->getMethod() == 'POST') {
            $session->remove('chgmtsfanta_page');
            $session->remove('chgmtsfanta_sort');
            $session->remove('chgmtsfanta_dir');
            $session->remove('changementControllerFilternew');
            if ($request->get('submit-filter') == "reset") {
                $session->getFlashBag()->add('warning', "Filtres de recherche reinitialisés");
            }
            //-----------------------------------------
            // On recupere les vars de post ==> session filter
            //------------------------------------------
            elseif ($request->get('submit-filter') == "filter") {
                $session->remove('changementControllerFilternew');
                $alldatas = $request->request->all();
                $datas = $alldatas["changements_searchfilter"];
                 //echo "submit filter<br"; print_r($datas);exit(1);
                $parameters = $datas;
                $session->set('changementControllerFilternew', $datas);
                $searchForm->bind($datas);
            }

            $page = 1;
            $dir = 'DESC';
            $sort = 'a.id';
        }
        //-----------------------------------------
        // On recupere les vars de session filter
        //------------------------------------------
        elseif ($session->has('changementControllerFilternew')) {
            $datas = $session->get('changementControllerFilternew');
            $parameters = $datas;
            $searchForm->bind($datas);
            // $page=1;$dir='DESC';$sort='a.id';
            list($page, $dir, $sort) = $this->OrderfantaAction();
        } else {
            list($page, $dir, $sort) = $this->OrderfantaAction();
            //$page=1;$dir='DESC';$sort='a.id';
        }

        if (!$page) {
            $page = 1;
        }

        //   exit(1);
        //-----------------------------------------
        // On recupere les vars de session page,dir,order
        //------------------------------------------
        //list($page, $dir, $sort) = $this->OrderfantaAction();
        $user_id = $this->getuserid();
        if (isset($user_id)) {
            $parameters['user_favoris'] = $user_id;
            //   print_r($parameters);
        }
        // $parameters['truc'] = 'tzrez';
        $next_dir = ($dir == 'DESC') ? 'ASC' : 'DESC';
        $arrow[$sort] = $next_dir == "DESC" ? 'icon-arrow-up' : 'icon-arrow-down';
        //   $parameters['truc'] = 'tzrez';
        $query = $em->getRepository('ApplicationChangementsBundle:Changements')->getJoinedBy($sort, $dir, $parameters);
        $adapter = new DoctrineORMAdapter($query);
        //$adapter->setDistinct(false);
        // sur changement categories avec filtres la page n'est peut etre
        // plus dispo (avec les sessions) !!!!!!!!!!
        // A debugger
        $pagerfanta = $this->mypager($adapter, 20);
        $nb_pages = $pagerfanta->getNbPages();
        if ($page > $nb_pages) {
            $session = $this->getRequest()->getSession();
            //$session->getFlashBag()->add('warning', "Enregistrement  update successfull");
            $session->getFlashBag()->add('warning', "Page $page n'exite pas: goto Page1");
            $page = 1;
        }

        try {
            $pagerfanta->setCurrentPage($page);
            $nbResults = $pagerfanta->getNbResults();
            // $nbResults=317;
            $q = $pagerfanta->getCurrentPageResults();
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
            // throw $this->createNotFoundException('Unable to find entity.');
        }
        return $this->render('ApplicationChangementsBundle:Changements:indexmyfanta.html.twig', array(
                    'pagerfanta' => $pagerfanta,
                    'entities' => $q,
                    'next_dir' => $next_dir,
                    'search_form' => $searchForm->createView(),
                    'status_form' => $statusForm->createView(),
                    'arrow' => $arrow,
                    'nb_pages' => $nb_pages,
                    'nbResults' => $nbResults,
                    'user_id' => $user_id,
             'nb_status' => $changements_nb_status
        ));
    }

    //==============================================
    // RECUP XHTML DES OPERATIONS LIEES A UNE DATE
    // de type: year,month
    //==============================================

    public function CalendarEventsAction() {

        $request = $this->getRequest();
        //$session = $this->getRequest()->getSession();
        $current_date = new \DateTime();
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $month = $request->request->get('month');
            if (!isset($month)) {
                $month = $current_date->format('m');
            } else {
                if ($month < 10)
                    $month = "0" . $month;
            }
            $year = $request->request->get('year');
            if (!isset($year)) {
                $year = $current_date->format('Y');
            }
            $date = $year . '-' . $month;
            $em = $this->getDoctrine()->getManager();

            // $session_event=$date;
            $events_date = $em->getRepository('ApplicationChangementsBundle:Changements')->getMyDate($date);
            $response = new Response(json_encode($events_date));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        // return new Response();
    }

}
