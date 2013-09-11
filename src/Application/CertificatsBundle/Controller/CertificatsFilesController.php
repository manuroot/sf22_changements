<?php

namespace Application\CertificatsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\CertificatsBundle\Model\MyOpenSsl;
use Application\CertificatsBundle\Entity\CertificatsFiles;
use Application\CertificatsBundle\Form\CertificatsFilesType;
use Application\CertificatsBundle\Form\CertificatsFilesAddType;
use Application\CertificatsBundle\Form\CertificatsFilesFilterType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\CertificatsBundle\Entity\CertificatsCenter;
use Application\CertificatsBundle\Entity\CertificatsActions;
use Application\CertificatsBundle\Form\CertificatsCenterType;

/* use APY\DataGridBundle\Grid\Source\Entity;
  use APY\DataGridBundle\Grid\Grid;
  use APY\DataGridBundle\Grid\Column\ActionsColumn;
  use APY\DataGridBundle\Grid\Action\MassAction;
  use APY\DataGridBundle\Grid\Action\DeleteMassAction;
  use APY\DataGridBundle\Grid\Action\RowAction;
  use APY\DataGridBundle\Grid\Column\TextColumn;
  use APY\DataGridBundle\Grid\Column\DateColumn;
  use APY\DataGridBundle\Grid\Export\CSVExport;
  use APY\DataGridBundle\Grid\Export\ExcelExport;
 *  
 */

/*
  use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
  use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
  use Symfony\Component\Security\Acl\Permission\MaskBuilder;
 * 
 */
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * CertificatsFiles controller.
 *
 */
class CertificatsFilesController extends Controller {
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
        $pagination->setTemplate('ApplicationCertificatsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $pagination;
    }

    /**
     * Lists all CertificatsFiles entities.
     *
     */
    public function indexoldAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->findAll();

        return $this->render('ApplicationCertificatsBundle:CertificatsFiles:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    public function indexooldAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'certificats_documents');
        $query = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->myFindAll();
        $pagination = $this->createpaginator($query, 10);
        $count = $pagination->getTotalItemCount();
        return $this->render('ApplicationCertificatsBundle:CertificatsFiles:index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

    public function indexAction(Request $request) {

        //  $entity = new Changements();
        $parameters = array();
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'certificats_documents');

        $searchForm = $this->createForm(new CertificatsFilesFilterType($em));

        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "reset") {
            $session->remove('certificatsfiles_filter');
        } elseif ($request->getMethod() == 'POST' && $request->get('submit-filter') == "filter") {
            $alldatas = $request->request->all();
            $datas = $alldatas["certificatsfiles_searchfilter"];


            //  print_r($datas);exit(1);



            $parameters = $datas;
            $session->set('certificatsfiles_filter', $datas);
            $searchForm->bind($datas);
        } else {
            if ($session->has('certificatsfiles_filter')) {
                $datas = $session->get('certificatsfiles_filter');
                $parameters = $datas;
                $searchForm->bind($datas);
            }
        }
        $query = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->getListBy($parameters);
        $pagination = $this->createpaginator($query, 15);
        $total = $pagination->getTotalItemCount();
        return $this->render('ApplicationCertificatsBundle:CertificatsFiles:index.html.twig', array(
                    'search_form' => $searchForm->createView(),
                    'pagination' => $pagination,
                    'total' => $total
        ));
    }

    /*
     * 
     *  if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "reset") {
      $session->remove('certificatsfiles_filter');
      } elseif ($request->getMethod() == 'POST' && $request->get('submit-filter') == "filter") {
      $alldatas = $request->request->all();
      $datas = $alldatas["certificatsfiles_searchfilter"];


      //  print_r($datas);exit(1);



      $parameters = $datas;
     * 
     * 
     * 
     *  if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "filter") {
      $alldatas = $request->request->all();
      $datas = $alldatas["certificats_filter"];
      $filterForm->bind($datas);
      if ($filterForm->isValid()) {
     */

    private function LierCertificat($datas) {


        $em = $this->getDoctrine()->getManager();
        $entity_typecert = $em->getRepository('ApplicationCertificatsBundle:CertificatsFileType')->find($data['typeCert']);

        $entity = new CertificatsCenter();
        $entity->setPort('80');
        $entity->setTypeCert($entity_typecert);
    }

    /**
     * Creates a new CertificatsFiles entity.
     *
     * Array ( [name] => [typeCert] => [creer_demande] => 1 
     * [certificats] => [_token] => ccddaa2110a6e1919f8715870f8ae661dc506d92 ) 
     * 
     * 
     * 
     * 
     * 
     * 
     */
    public function createAction(Request $request) {
        $entity_fichier = new CertificatsFiles();
        $form = $this->createForm(new CertificatsFilesAddType(), $entity_fichier);

        if ($request->getMethod() == 'POST') {
            $alldatas = $request->request->all();
            $datas = $alldatas["fichier_certificat"];


            /* print_r($datas);
              exit(1); */
            $form->bind($request);

            if ($form->isValid()) {
                // recup des champs du formluaire
                //verif si creation d'une entree
                //verif si associé a entree existante
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity_fichier);
                $em->flush();

                // creer unde demande avec ce fichier
                if ($datas['creer_demande'] == 1) {

                    $em = $this->getDoctrine()->getManager();
                    $entity_typecert = $em->getRepository('ApplicationCertificatsBundle:CertificatsFileType')->find($datas['typeCert']);
                    $entity_file = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($entity_fichier->getId());

                    $entity_certificat = new CertificatsCenter();
                    $entity_certificat->setPort('80');
                    $entity_certificat->setFileName('temp');
                    $entity_certificat->setTypeCert($entity_typecert);
                    $entity_certificat->setFichier($entity_file);
                    $form = $this->createForm(new CertificatsCenterType(), $entity_certificat);

                    $session = $this->getRequest()->getSession();
                    $myretour = $session->get('buttonretour');
                    if (!isset($myretour)) {
                        $myretour = 'certificatscenter';
                    }
                    return $this->render('ApplicationCertificatsBundle:CertificatsCenter:new.html.twig', array(
                                'entity' => $entity_certificat,
                                'form' => $form->createView(),
                                'btnretour' => $myretour,
                        'fichier' => $entity_file,
                               
                    ));
                }
                return $this->redirect($this->generateUrl('certificats_documents_show', array('id' => $entity_fichier->getId())));
            }
        }
        return $this->render('ApplicationCertificatsBundle:CertificatsFiles:new.html.twig', array(
                    'entity' => $entity_fichier,
                    'form' => $form->createView(),
        ));
    }

    /** ===================================================================
     * 
     *  CREATE ENREGISTREMENT $ID
     * 
     * @Secure(roles="ROLE_USER")
     * 
     * 
     * $data['moncert']=
     * 
     * Array ( [fileName] => gjhghj 
     * [cnName] => ghjgjh [serviceName] => gjhg 
     * [serverName] => gjhjh [port] => 80 [way] => op 
     * [statusFile] => 1 [description] => 123123 [addedDate] => 2013-09-11 
     * [startDate] => 2013-09-09 [endTime] => 2013-09-16 [typeCert] => 10 [demandeur] => 16 
     * [project] => 12 [idEnvironnement] => 2 [idapplis] => Array ( [0] => 31 )
     *  [_token] => ccddaa2110a6e1919f8715870f8ae661dc506d92 ) 
      =================================================================== */
    private function createCertificatAction($datas = array()) {
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
            return $this->redirect($this->generateUrl('certificatscenter'));
            //return $this->redirect($this->generateUrl('certificatscenter_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'btnretour' => $myretour,
        ));
    }

    /**
     * Displays a form to create a new CertificatsFiles entity.
     *
     */
    public function newAction() {
        $entity = new CertificatsFiles();
        $form = $this->createForm(new CertificatsFilesAddType(array('port' => 80)), $entity);


        return $this->render('ApplicationCertificatsBundle:CertificatsFiles:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CertificatsFiles entity.
     *
     */
    public function showAction($id) {
        $cmd_x509 = null;
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsFiles entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        $openssl = new MyOpenSsl();
        //   $x509=$openssl->View_Cert($name);
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

    /**
     * Displays a form to edit an existing CertificatsFiles entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsFiles entity.');
        }

        $editForm = $this->createForm(new CertificatsFilesAddType(), $entity);

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:CertificatsFiles:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing CertificatsFiles entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsFiles entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CertificatsFilesAddType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('certificats_documents_edit', array('id' => $id)));
        }

        return $this->render('ApplicationCertificatsBundle:CertificatsFiles:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Docchangements entity.
     *
     */
    /*  public function updateAction(Request $request, $id) {

      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id);
      if (!$entity) {
      throw $this->createNotFoundException('Unable to find Docchangements entity.');
      }
      $current_changements = clone $entity->getIdchangement();

      // recup des changements
      //$changements = $entity->getIdchangement();
      $deleteForm = $this->createDeleteForm($id);
      $editForm = $this->createForm(new DocchangementsType(), $entity);
      $editForm->bind($request);

      if ($editForm->isValid()) {
      // on vide cote changement
      // ou passer par byreference a false dans le formulaire
      foreach ( $current_changements as $change ){
      $change->getPicture()->removeElement( $entity );
      $em->persist($change);
      }
      //$entity = $em->getRepository('ApplicationChangementsBundle:Docchangements')->find($id);
      // on ajoute cote changement
      foreach ($entity->getIdchangement() AS $changement){
      $changement->addPicture($entity);
      }
      // on persite coté document
      $em->persist($entity);
      $em->flush();
      $session = $this->getRequest()->getSession();
      $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");
      return $this->redirect($this->generateUrl('docchangements_edit', array('id' => $id)));
      }

      return $this->render('ApplicationChangementsBundle:Docchangements:edit.html.twig', array(
      'entity' => $entity,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
      ));
      } */

    /**
     * Deletes a CertificatsFiles entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CertificatsFiles entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('certificats_documents'));
    }

    /**
     * Creates a form to delete a CertificatsFiles entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /* ====================================================================
     * 
     *  DOWNLOAD CERT
     * 
     * @Secure(roles="ROLE_ADMIN")
      =================================================================== */

    public function downloadAction($id) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docchangements entity.');
        }

        $request = $this->get('request');
        //   $url='docchangements';
        $session = $request->getSession();
        $url = $session->get('buttonretour');
        if (!isset($url))
            $url = 'certificatscenter';
        $filename = $entity->getPath();
        $realname = $entity->getOriginalFilename();
        if (!isset($realname))
            $realname = $filename;
        $path = $this->get('kernel')->getRootDir() . "/../" . $entity->getDownloadDir();
        if (!file_exists($path . $filename)) {
            // $session->getFlashBag()->add('error', "Le fichier $path/$filename n 'existe pas (code 1)");
            $session->getFlashBag()->add('error', "Le fichier $filename n 'existe pas (code 1)");
            return $this->redirect($this->generateUrl($url));
        }

        try {
            $content = file_get_contents($path . $filename);
        } catch (\ErrorException $e) {
            $session->getFlashBag()->add('error', "Le fichier $filename n 'existe pas (code 2)");
            return $this->redirect($this->generateUrl($url));
        }
        $response = new Response();

        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $realname);
        $session->getFlashBag()->add('notice', "Le fichier $filename a ete téléchargé");

        $response->setContent($content);
        return $response;
    }

    public function NomAjaxAction(Request $request) {
        $term = $request->get('term');
        $em = $this->getDoctrine()->getManager();
        $entity_ticket = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->findAjaxValue(array('OriginalFilename' => $term));
        $json = array();
        foreach ($entity_ticket->getQuery()->getResult() as $ticket) {
            if (!in_array((string) $ticket->getOriginalFilename(), $json))
                array_push($json, (string) $ticket->getOriginalFilename());
        }
        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}

