<?php

namespace Application\CertificatsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Application\CertificatsBundle\Entity\CertificatsCenter;
use Application\CertificatsBundle\Entity\CertificatsActions;
use Application\CertificatsBundle\Form\CertificatsCenterType;
use Application\CertificatsBundle\Form\CertificatsActionsType;
use Application\CertificatsBundle\Form\CertificatsCenterCheckType;
use Application\CertificatsBundle\Form\CertificatsCenterFiltresType;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Export\ExcelExport;
use Application\CertificatsBundle\Model\MyOpenSsl;
use Application\CentralBundle\Model\MesFiltres;
use Application\CertificatsBundle\Entity\CertificatsFiles;

//use APY\DataGridBundle\Grid\Export\PHPExcelPDFExport;
//use APY\DataGridBundle\Grid\Export\ExcelExport;

/**
 * CertificatsCenter controller.
 *
 */
class CertificatsCenterController extends Controller {

    /**
     * Lists all CertificatsCenter entities.
     *
     */
    public static function myStaticMethod(array $ids) {
        // Do whatever you want with these ids
    }

    public function myMethod(array $ids) {
        // Do whatever you want with these ids
    }

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

    protected function filter() {
        //  $message = "filter datas";
        $message = "";
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new CertificatsCenterFiltresType());
        $filterBuilder = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->myFindaAll();
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "reset") {
            //  $message = "reset filtres";
            $session->remove('certificatsControllerFilter');
            $query = $filterBuilder;
            return array($filterForm, $query, $message);
        }

        // datas filter
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "filter") {
            $alldatas = $request->request->all();
            $datas = $alldatas["certificats_filter"];
            $filterForm->bind($datas);
            if ($filterForm->isValid()) {
                // $message .= " - filtre valide";
                $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $filterBuilder);
                $session->set('certificatsControllerFilter', $datas);
            } else {
                $query = $filterBuilder;
            }
            return array($filterForm, $query, $message);
        } else {
            if ($session->has('certificatsControllerFilter')) {

                $datas = $session->get('certificatsControllerFilter');
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

    public function indexAction(Request $request) {

        $date_warning = array(7, 15);
        $message = "";
        //$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'certificatscenter');

        list($filterForm, $queryBuilder, $message) = $this->filter();
        if ($message)
            $session->getFlashBag()->add('warning', "$message");
        $pagination = $this->createpaginator($queryBuilder, 15);
        $count = $pagination->getTotalItemCount();
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:index.html.twig', array(
                    'search_form' => $filterForm->createView(),
                    'pagination' => $pagination,
                    'date_warning' => $date_warning,
                    'total' => $count,
        ));
    }

    /*
      return $this->render('ApplicationCertificatsBundle:CertificatsCenter:index.html.twig', array(
      'pagination' => $pagination,
      'search_form' => $searchForm->createView(),
      )); */

//return compact('pagination');

    public function indexoldAction() {


        /*
          $message = \Swift_Message::newInstance()
          ->setSubject('Hello Email')
          ->setFrom('send@example.com')
          ->setTo('mroot72000@yahoo.fr')
          ->setBody(
          $this->renderView(
          'ApplicationCertificatsBundle:CertificatsCenter:email.txt.twig'
          )
          )
          ;
          $this->get('mailer')->send($message); */
        /* $session = new Session();
          $session->start();
          // définit et récupère des attributs de session
          $session->set('name', 'Drak');
          $session->get('name');

          // définit des messages dits « flash »
          $session->getFlashBag()->add('notice', 'Profile updated'); */
//$this->get('session')->getFlashBag()->set('type', 'message');
        // ajoute des messages flash
        /* $session->getFlashBag()->add('warning', 'Your config file is writable, it should be set read-only');
          $session->getFlashBag()->add('error', 'Failed to update name');
          $session->getFlashBag()->add('error', 'Another error'); */
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'certificatscenter');
        $searchForm = $this->createForm(new CertificatsCenterFiltresType());
        if ($this->get('request')->query->has('submit-filter')) {
            // echo "submit filters";exit(1);
            // bind values from the request
            $searchForm->bind($this->get('request'));
            $filterBuilder = $this->get('doctrine.orm.entity_manager')
                            ->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->myFindaAll();
            $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($searchForm, $filterBuilder);
            //   var_dump($filterBuilder->getDql());exit(1);
        } else {
            $em = $this->getDoctrine()->getManager();
            $query = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->myFindaAll();
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        $pagination->setTemplate('ApplicationCertificatsBundle:pagination:sliding.html.twig');
        //$pagination->setTemplate('ApplicationMyNotesBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:index.html.twig', array(
                    'pagination' => $pagination,
                    'search_form' => $searchForm->createView(),
        ));
//return compact('pagination');
    }

    public function checkcertAction() {

        $entity = new CertificatsActions();
        //  $myForm = $this->createForm(new CertificatsActionsType(),$entity);
        $form = $this->createForm(new CertificatsCenterCheckType());
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:checkcert.html.twig', array(
                    'form' => $form->createView(),
                        // 'myform'=> $myForm->createView(),
        ));
    }

    public function validatecheckcertAction(Request $request) {

        //  $entity=new CertificatsActions();
        // $myForm = $this->createForm(new CertificatsActionsType(),$entity);

        $vForm = $this->createForm(new CertificatsCenterCheckType());
        if ($request->getMethod() == 'POST') {
            $postData = $request->request->get('checkcert');
            $vForm->bind($postData);
            $ssl = new MyOpenSsl();
            $all_ope = $ssl->getOperations();
            $all_fic = $ssl->getFichiers();
            $arr = array($all_ope, $all_fic);
            var_dump($postData);
            var_dump($arr);
            // post:
            //["opecert"]=> string(1) "3" ["typecert"]=> string(1) "4" ["contenu"]=> string(7) "hgfgffg" [
       
            $current_ope=$all_ope[$postData['opecert']];
                    $current_fic=$all_fic[$postData['typecert']];
                    $current_data=$postData['contenu'];
                    $arr_current=array(
                        'ope'=>$current_ope,
                        'cert'=>$current_fic,
                        'data'=>$current_data,
                        );
                         var_dump($arr_current);
                          exit(1);
            return $this->render('ApplicationCertificatsBundle:CertificatsCenter:checkcert.html.twig', array(
                        'datas' => $postData,
                        'form' => $vForm->createView(),
                            // 'myform'=> $myForm->createView(),
            ));

    
            // }
        }
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:validatecert.html.twig', array(
                        // 'myform'=> $myForm->createView(),
        ));
    }

    /**
     * Finds and displays a CertificatsCenter entity.
     *
     */
    public function showAction(Request $request, $id) {
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id);
        $applis = $entity->getIdapplis();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsCenter entity.');
        }

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            return $this->render('ApplicationCertificatsBundle:CertificatsCenter:showxhtml.html.twig', array(
                        'entity' => $entity,
                        'btnretour' => $myretour,
                        'delete_form' => $deleteForm->createView(),
                        'applis' => $applis,
            ));
        }
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:show.html.twig', array(
                    'entity' => $entity,
                    'btnretour' => $myretour,
                    'delete_form' => $deleteForm->createView(),
                    'applis' => $applis,
        ));
    }

    /*
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
      } */

    /**
     * Displays a form to create a new CertificatsCenter entity.
     *
     */
    public function newAction() {
        $entity = new CertificatsCenter();
        $form = $this->createForm(new CertificatsCenterType(), $entity);
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');
        if (!isset($myretour)) {
            $myretour = 'certificatscenter';
        }
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'btnretour' => $myretour,
            'fichier'=>null,
        ));
    }

    /**
     * Creates a new CertificatsCenter entity.
     *
     * @Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request) {
        $entity = new CertificatsCenter();
        $form = $this->createForm(new CertificatsCenterType(), $entity);
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // creating the ACL
            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($entity);
            $acl = $aclProvider->createAcl($objectIdentity);

            // retrieving the security identity of the currently logged-in user
            $securityContext = $this->get('security.context');
            $user = $securityContext->getToken()->getUser();
            $securityIdentity = UserSecurityIdentity::fromAccount($user);
            /*  $builder = new MaskBuilder();
              $builder
              ->add('view')
              ->add('edit')
              ->add('delete')
              ->add('undelete')
              ;
              $mask = $builder->get(); // int(29) */
            // grant owner access
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);
            $session = $this->getRequest()->getSession();
            // ajoute des messages flash
            $id = $entity->getId();
            $session->getFlashBag()->add('warning', "Enregistrement $id ajout successfull");

            return $this->redirect($this->generateUrl('certificatscenter_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'btnretour' => $myretour,
        ));
    }

    /**
     * Displays a form to edit an existing CertificatsCenter entity.
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editAction($id) {

        $em = $this->getDoctrine()->getManager();
        // stocke un attribut pour une réutilisation lors d'une future requête utilisateur
        //$session->set('buttonretour', 'bar');
        // dans un autre contrôleur pour une autre requête
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');
        if (!isset($myretour)) {
            $myretour = 'certificatscenter';
        }
        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id);
        // check rights
        $securityContext = $this->get('security.context');


        // Soit owner du record soit admin
        if (false === $securityContext->isGranted('OWNER', $entity) && false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        //      if ($securityIdentity == $acl)
        /*   if ($this->securityContext->isGranted('OWNER', $this->objectIdentity) === false) {

          }
          if ($acl->isGranted( array(MaskBuilder::MASK_OWNER), array( $securityIdentity) ))

          {
          throw new AccessDeniedException();
          }
         */

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsCenter entity.');
        }

        $editForm = $this->createForm(new CertificatsCenterType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $fichier=$entity->getFichier();
       
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'btnretour' => $myretour,
            'fichier'=>$fichier,
                        //   'acl'=>$acl,
                        //  'idty'=>$securityIdentity,
        ));
    }

    /**
     * Edits an existing CertificatsCenter entity.
     *
     * @Secure(roles="ROLE_USER")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

         $session = $request->getSession();
        $session->set('buttonretour', 'certificatscenter');

        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsCenter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CertificatsCenterType(), $entity);
       // $postData = $request->request->all;
$postData = $this->getRequest()->request;        
        $uploadedFile = $request->files->get('moncert');
        if ($uploadedFile['fichier']['file'] != NULL) {
               print_r($uploadedFile['fichier']['file']);
                $entity->setFichier(NULL);
               //$userProfile->setPicture(NULL);
        }else {
            // si pas de fichier en base
            echo "pas de fichier";
            $fichier_entity=$entity->getFichier();
            if (!$fichier_entity){
             //   $entity->setFichier(null);
                
                echo "<br>Nok fichier pas en base<br>";
            //    unset($request["moncert"]["fichier"]);
            }
            else { echo "<br>ok fichier deja en base<br>";}
        }
      //  echo "<br>datas?<br>";
     //   var_dump($postData);
        //   unset($postData['id']);
             // var_dump($postData);
              //print_r($postData);
//exit(1);
       // non sinon il en manque !!
    //    $editForm->bind($postData);
    $editForm->bind($request);

  
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $session = $this->getRequest()->getSession();
            // ajoute des messages flash
            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");

            //  $session = $this->getRequest()->getSession();
            $route_back = $session->get('buttonretour');
            if (isset($route_back))
                return $this->redirect($this->generateUrl($route_back, array('id' => $id)));
            else
                return $this->redirect($this->generateUrl('certificatscenter'));
        }
        // pas valide
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
            'btnretour'=>'certificatscenter',
        ));
    }

    /**
     * Deletes a CertificatsCenter entity.
     *
     */
    //==============================================
    // SUPPRIMER ACTEUR
    //==============================================

    /**
    * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction($id) {


        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');


        $em = $this->getDoctrine()->getManager();
        $em = $this->container->get('doctrine')->getManager();
        $cert = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id);
        //$cert = $em->find('ApplicationCertificatsBundle:CertificatsCenter', $id);
        if (!$cert) {
            throw new NotFoundHttpException("Note non trouvée");
        }

        $aclProvider = $this->get('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($cert);
        $aclProvider->deleteAcl($objectIdentity);

        /*
          $aclProvider = $this->get('security.acl.provider');
         * $aclProvier->deleteAcl($objectIdentity) 
          $objectIdentity = ObjectIdentity::fromDomainObject($cert);
          $acl = $aclProvider->createAcl($objectIdentity);
          $aclProvider->deleteAcl($acl);
         */
        $em->remove($cert);
        $em->flush();

        return $this->redirect($this->generateUrl('certificatscenter_viewapy'));
        // return new RedirectResponse($this->container->get('router')->generate('notesfanta'));
    }

    //==============================================
    // SUPPRIMER CERTS AUTOGENERATION
    // not working
    //==============================================

    public function deleteActionaa(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CertificatsCenter entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('certificatscenter'));
    }

    protected function createSearchForm() {

        return $this->createFormBuilder()
                        ->add('Serveur', 'text', array('label' => 'Serveur'))
                        ->add('amount', 'choice', array(
                            'label' => 'Montant en euros',
                            'choices' => array(
                                1 => 1,
                                2 => 2,
                                10 => 10, 20 => 20, 50 => 50, 100 => 100, 200 => 200),
                            'preferred_choices' => array(10),
                        ))
                        //->add('currency', null, array('data' => 'EUR', 'label' => 'Devise'))
                        ->add('item_name', 'hidden', array(
                            'data' => 'Participation Au Blog MROOT',
                        ))
                        ->getForm()
        ;
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm();
    }

    //==============================================
    // VIEW ALL ACTEURS
    //==============================================
    public function viewapyAction($page = 1) {

        $session = $this->getRequest()->getSession();
        // ajoute des messages flash
        $session->set('buttonretour', 'certificatscenter_viewapy');
        $source = new Entity('ApplicationCertificatsBundle:CertificatsCenter');
        $source->manipulateRow(
                function ($row) {
                    // Don't show the row if the price is greater than $maxPrice
                    $past = date('Y-m-d', strtotime('+30days'));
                    $currenta = ($row->getField('endTime')->format('Y-m-d'));
                    $current = date('Y-m-d', strtotime($currenta));
                    //$current = new \DateTime($row->getField('endTime')->format('Y-m-d'));
                    //$current = date('Y-m-d', strtotime($row->getField('endTime')));
                    if ($current < $past) {
                        $row->setColor('#fcf8e3');
                    }

                    return $row;
                }
        );
        $grid = $this->container->get('grid');
        // Attach the source to the grid
        $grid->setSource($source);

        $grid->setId('certificatsgrid');
        $grid->addExport(new CSVExport('CSV Export'));
        $grid->addExport(new ExcelExport('Excel Export'));
        // $grid->addExport(new PHPExcelPDFExport('Simple PDF Export'));
        //chiant si error
        $grid->setPersistence(true);
        $grid->setDefaultOrder('id', 'desc');
        // Set the selector of the number of items per page
        $grid->setLimits(array(15));

        // Set the default page
        $grid->setPage($page);
        $grid->addMassAction(new DeleteMassAction());
        $grid->setActionsColumnSize(100);
        $myRowActiona = new RowAction('Edit', 'certificatscenter_edit', false, '_self', array('class' => "btn btn-mini btn-warning"));
        $grid->addRowAction($myRowActiona);
        $myRowAction = new RowAction('Delete', 'certificatscenter_delete', true, '_self', array('class' => "btn btn-mini btn-danger"));
        //$myRowAction = new RowAction('Delete', 'certificatscenter_delete', true, '_self',array('class' => 'deleteme'));
        $grid->addRowAction($myRowAction);
        // Return the response of the grid to the template
        return $grid->getGridResponse('ApplicationCertificatsBundle:CertificatsCenter:indexapy.html.twig');
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

    public function update_certificats_statusAction() {
        $request = $this->get('request');
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $id = $request->request->get('id');
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->findOneById($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Cert entity.');
            }
            $id_warning = $entity->getWarningFile();
            if ($id_warning) {
                $entity->setWarningFile(false);
             } else {
                $entity->setWarningFile(true);
             }
             $em->persist($entity);
             $em->flush();
            $array = array('mystatus' => $id_warning);
            //   $array=array($array);
            $response = new Response(json_encode($array));
           $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

}
