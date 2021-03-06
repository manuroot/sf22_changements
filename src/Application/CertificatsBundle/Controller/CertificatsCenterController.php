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
use Application\CertificatsBundle\Form\CertificatsCenterSimpleCheckType;
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
//use APY\DataGridBundle\Grid\Export\PHPExcelPDFExport;
//use APY\DataGridBundle\Grid\Export\ExcelExport;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Export\ExcelExport;
use Application\CertificatsBundle\Model\MyOpenSsl;
use Application\CentralBundle\Model\MesFiltres;
use Application\CertificatsBundle\Entity\CertificatsFiles;

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
        $pagination->setSortableTemplate('ApplicationCertificatsBundle:pagination:sortable_link.html.twig');
        // $pagination->setSortableTemplate('ApplicationRelationsBundle:pagination:sortable_link.html.twig');
        $pagination->setTemplate('ApplicationCertificatsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $pagination;
    }

    /* ====================================================================
     * 
     *  FILTRES DE RECHERCHE
     * 
      =================================================================== */

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

    /* ====================================================================
     * 
     *  INDEX (TABLE MAIN)
     * 
      =================================================================== */

    public function indexAction(Request $request) {
        $date_warning = array(7, 15);
        $message = "";
        //$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'certificatscenter');
        list($filterForm, $query, $message) = $this->filter();
        $queryBuilder = $query->getQuery();
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

     /* ====================================================================
     * 
     *  VERIFICATION FICHIER SIMPLE
     * 
      =================================================================== */

    public function simplecheckcertAction(Request $request) {
        $entity = new CertificatsActions();
        //  $myForm = $this->createForm(new CertificatsActionsType(),$entity);
        $form = $this->createForm(new CertificatsCenterSimpleCheckType());
       
        if ($request->getMethod() == 'POST') {
            $postData = $request->request->get('checkcert');
          /*  $form->bind($postData);
            $ssl = new MyOpenSsl();
            $all_ope = $ssl->getOperations();
            $all_fic = $ssl->getFichiers();
            $arr = array($all_ope, $all_fic);*/
            var_dump($postData);
            //  var_dump($arr);
       
        }
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:index_simplecheck.html.twig', array(
                    'form' => $form->createView(),
                        // 'myform'=> $myForm->createView(),
        ));
    }
    
    /* ====================================================================
     * 
     *  VERIFICATION CERT
     * 
      =================================================================== */

    public function checkcertAction() {
        $entity = new CertificatsActions();
        //  $myForm = $this->createForm(new CertificatsActionsType(),$entity);
        $form = $this->createForm(new CertificatsCenterCheckType());
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:checkcert.html.twig', array(
                    'form' => $form->createView(),
                        // 'myform'=> $myForm->createView(),
        ));
    }

    /* ====================================================================
     * 
     *  VALIDER CHECK CERT
     * 
      =================================================================== */

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
            //  var_dump($arr);
            // post:
            //["opecert"]=> string(1) "3" ["typecert"]=> string(1) "4" ["contenu"]=> string(7) "hgfgffg" [

            $current_ope = $all_ope[$postData['opecert']];
            $current_fic = $all_fic[$postData['typecert']];
            $current_data = $postData['contenu'];
            $arr_current = array(
                'ope' => $current_ope,
                'cert' => $current_fic,
                'data' => $current_data,
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

    /** ===================================================================
     * 
     *  SHOW ENREGISTREMENT $ID
     * 
     * =================================================================== */

    /**
     *  @Secure(roles="ROLE_USER")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $id
     * @return type
     * @throws type
     * 
     */
    public function showAction(Request $request, $id) {
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');
        $em = $this->getDoctrine()->getManager();
        $cmd_x509 = null;
        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id);
        $applis = $entity->getIdapplis();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsCenter entity.');
        }
        $fichier = $entity->getFichier();
        if ($fichier) {
            $openssl = new MyOpenSsl();
            $filename = $fichier->getPath();
            $path = $this->get('kernel')->getRootDir() . "/../" . $fichier->getDownloadDir();
            $fic = $path . $filename;
            if (file_exists($fic)) {
                $cmd_x509 = $openssl->View_Cert($fic);
            }
        }
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST')
            $html = 'ApplicationCertificatsBundle:CertificatsCenter:showxhtml.html.twig';
        else
            $html = 'ApplicationCertificatsBundle:CertificatsCenter:show.html.twig';

        $deleteForm = $this->createDeleteForm($id);
        return $this->render($html, array(
                    'entity' => $entity,
                    'btnretour' => $myretour,
                    'delete_form' => $deleteForm->createView(),
                    'applis' => $applis,
                    'cmd_x509' => $cmd_x509,
        ));
    }

    /*
     * 
     */
    /*
     *  $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id);
      if (!$entity) {
      throw $this->createNotFoundException('Unable to find CertificatsFiles entity.');
      }
      $deleteForm = $this->createDeleteForm($id);
      $openssl = new MyOpenSsl();
      $filename = $entity->getPath();
      $path = $this->get('kernel')->getRootDir() . "/../" . $entity->getDownloadDir();
      $fic = $path . $filename;
      if (file_exists($fic)) {
      $cmd_x509 = $openssl->View_Cert($fic);
      }
      return $this->render('ApplicationCertificatsBundle:CertificatsFiles:show.html.twig', array(
      'entity' => $entity,
      'cmd_x509' => $cmd_x509,
      'delete_form' => $deleteForm->createView(),));
      }
     */

    /** ===================================================================
     * 
     *  NEW ENREGISTREMENT 
     * 
     * @Secure(roles="ROLE_USER")
     * 
     * @return type
     * 
     */
    public function newAction() {
        $entity = new CertificatsCenter();
        // $data['moncert']['port']='80';
        //  $entity->setPort('80');
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
                    'fichier' => null,
        ));
    }

    /** ===================================================================
     * 
     *  CREATE ENREGISTREMENT $ID
     * 
     * @Secure(roles="ROLE_USER")
     * 
     * 
     * Formulaire: moncert
      =================================================================== */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $entity = new CertificatsCenter();
        $form = $this->createForm(new CertificatsCenterType(), $entity);
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');

        $alldatas = $request->request->all();
        $datas = $alldatas["moncert"];
        $uploadedFile = $request->files->get('moncert');
      //  print_r($alldatas);exit(1);
        print_r($uploadedFile);
        $id_fichier = $alldatas['myfile'];
        $form->bind($request);


        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            // Ajout du fichier si necessaire

            if ($uploadedFile['fichier']['file'] == NULL && isset($alldatas['myfile'])) {
                // echo "here";exit(1);
                $entity_fichier = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id_fichier);

                if (!$entity_fichier) {
                    throw $this->createNotFoundException('Unable to find CertificatsFiles entity.');
                }
                //    echo "original name=" . $uploadedFile['fichier']['file']->originalName . "<br>";
                //    print_r($uploadedFile['fichier']);
                //   exit(1);
                //     print_r($uploadedFile['fichier']['file']);
                $entity->setFichier($entity_fichier);
                $em->persist($entity);
                $em->flush();
                //$userProfile->setPicture(NULL);
            }
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
            return $this->redirect($this->generateUrl('certificatscenter'));
            //return $this->redirect($this->generateUrl('certificatscenter_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'btnretour' => $myretour,
        ));
    }

    /** ===================================================================
     * 
     *  UPDATE ENREGISTREMENT $ID
     * 
     * @Secure(roles="ROLE_USER")
     * 
     * @param type $id
     * @return type
     * @throws AccessDeniedException
     * @throws type
     * 
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
        //peut mieux faire (17 sql request)
        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id);
        // $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->myFindaAll($id); 
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

        $fichier = $entity->getFichier();

         $fentity = new CertificatsFiles();
       //$form = $this->createForm(new CertificatsFilesType,$entity);

        $xform = $this->createFormBuilder($fentity)
       // ->add('name')
        ->add('file')
        ->getForm();
        
        
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:edit.html.twig', array(
                    'entity' => $entity,
                    'xform'=> $xform->createView(),
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'btnretour' => $myretour,
                    'fichier' => $fichier,
                        //   'acl'=>$acl,
                        //  'idty'=>$securityIdentity,
        ));
    }
 public function indexoperationsAction(Request $request) {

        
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:index_operations.html.twig', array(
                    
        ));
    }

    /** ===================================================================
     * 
     *  UPDATE ENREGISTREMENT $ID
     * 
     * @Secure(roles="ROLE_USER")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $id
     * @return type
     * @throws type
     * 
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
       $postData = $request->request->all();
       
         //var_dump($postData);exit(1);
        //$postData = $this->getRequest()->request;
        $uploadedFile = $request->files->get('moncert');
        $id_file = $request->get('myfile'); 
        $editForm->bind($request);
        
       // var_dump($id_file);exit(1);
        // si fichier link, lien vers certificats
        if (isset($id_file) && $id_file !=0){
         //   $entity->setFichier(NULL);
            $fichier_entity= $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id_file);
            $entity->setFichier($fichier_entity);
          
        }
        //=========================================
        // Si fichier a ete uploadé:
        // on mey le lien a null et on update
        //=========================================
        elseif ($uploadedFile['fichier']['file'] != NULL) {
            $entity->setFichier(NULL);
        }
        
        // non sinon il en manque !!
        //    $editForm->bind($postData);
       
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
        $fichier = $entity->getFichier();
        // form pas valide
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'btnretour' => 'certificatscenter',
                    'fichier' => $fichier,
        ));
    }

    /** ===================================================================
     * 
     *  DELETE ENREGISTREMENT $ID
     * 
     * @Secure(roles="ROLE_ADMIN")
     * 
     * @param type $id
     * @return type
     * @throws NotFoundHttpException
     * 
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
    // 
    // 
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

    //==============================================
    // SUPPRIMER CERTS :FORM DE BASE
    //==============================================

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

    /* ====================================================================
     * 
     *  AJAX RECHERCHE
     * 
     * A REVOIR
      =================================================================== */

    public function listByProjetAction() {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $applis = array();
            $cert_app = array();
            $applis['cert'] = array();
            $applis['applis'] = array();
            $id = $request->request->get('id_projet');

            if (isset($id) && $id != "") {
                //  echo "id ok:--$id--"; exit(1);
                $projet = $em->getRepository('ApplicationRelationsBundle:Projet')->find($id);
                $id_cert = $request->request->get('id_cert');

                if (isset($id_cert) && $id_cert != "") {
                    //  echo "cert ok";exit(1);
                    //var_dump($id_cert);
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
            }
            //    $appli=array(3,4);
            $response = new Response(json_encode($applis));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        // return new Response();
    }

    /* ====================================================================
     * 
     *  AJAX RECHERCHE
     * 
      =================================================================== */

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

    /* ====================================================================
     * 
     *  NOUVEAU CERT
     * 
      =================================================================== */

    public function newcertificatsfileAction() {
        $entity = new Docchangements();
        $form = $this->createForm(new DocchangementsType(), $entity);

        // si données postées
        if ($request->getMethod() == 'POST') {
            //  $postData = $request->request->get('checkcert');
            //  $vForm->bind($postData);
            if ($uploadedFile['fichier']['file'] != NULL) {
                //     print_r($uploadedFile['fichier']['file']);
                $entity->setFichier(NULL);
                //$userProfile->setPicture(NULL);
            }
        }

        return $this->render('ApplicationChangementsBundle:Docchangements:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    
      

}
