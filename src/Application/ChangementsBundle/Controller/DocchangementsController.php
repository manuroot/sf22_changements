<?php

namespace Application\ChangementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;




use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\ChangementsBundle\Entity\Changements;
use Application\RelationsBundle\Entity\Document;
use Application\ChangementsBundle\Entity\Docchangements;
use Application\ChangementsBundle\Form\DocchangementsType;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Application\ChangementsBundle\Form\DocchangementsFilterType;

/**
 * Docchangements controller.
 *
 */
class DocchangementsController extends Controller {

      /* ====================================================================
     * 
     *  CREATION DU PAGINATOR / Pagerfanta
     * 
      =================================================================== */
 private function mypager($adapter = null, $max = 5, $page = 1) {
        if (isset($adapter)) {
            $pagerfanta = new Pagerfanta($adapter);
            $pagerfanta->setMaxPerPage($max);

            return $pagerfanta;
        } else {
            return null;
        }
    }
    private function createpaginator($query, $num_perpage = 5,$count=null) {

        $paginator = $this->get('knp_paginator');
        //$paginator->setUseOutputWalkers(true);
         $query=$query->getQuery();
        $pagename = 'page'; // Set custom page variable name
        $page = $this->get('request')->query->get($pagename, 1); // Get custom page variable

        if (isset($count)){
           $total = 10;
       $query->setHint('knp_paginator.count', $total);
        }
          $pagination = $paginator->paginate(
                $query, $page, $num_perpage, array('pageParameterName' => $pagename,
                    'distinct'=>true,
            "sortDirectionParameterName" => "dir",
            'sortFieldParameterName' => "sort")
        );
        $pagination->setTemplate('ApplicationChangementsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        $pagination->setSortableTemplate('ApplicationChangementsBundle:pagination:sortable_link.html.twig');
        return $pagination;
    }
    /**
     * Lists all Docchangements entities.
     *
     */
    public function indexoldAction() {
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'docchangements');
        $em = $this->getDoctrine()->getManager();
        //$query = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindAll();

        $query = $em->getRepository('ApplicationChangementsBundle:Docchangements')->myFindAll();

        // $nbtags = $query->getPicture()->count();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
          $pagination->setTemplate('ApplicationChangementsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $this->render('ApplicationChangementsBundle:Docchangements:index.html.twig', array(
                    'pagination' => $pagination,
                ));
    }
    
     public function indexfantaAction(Request $request) {

         //  $entity = new Changements();
      $em = $this->getDoctrine()->getManager();
   /*      $json = $em->getRepository('ApplicationChangementsBundle:Docchangements')->findAjaxValue(array('nom' => 'ab'));
    exit(1);*/
        $parameters = array();
       
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'docchangements');
      
        $searchForm = $this->createForm(new DocchangementsFilterType());
     
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "reset") {
            $session->remove('docchangementFilternew');
        } elseif ($request->getMethod() == 'POST' && $request->get('submit-filter') == "filter") {
            $alldatas = $request->request->all();
            $datas = $alldatas["docchangements_searchfilter"];
            $parameters = $datas;
            $session->set('docchangementFilternew', $datas);
            $searchForm->bind($datas);
        } else {
            if ($session->has('docchangementFilternew')) {
                $datas = $session->get('docchangementFilternew');
                $parameters = $datas;
                $searchForm->bind($datas);
            }
        }
          // ajouter session + masquer parametres
        $sort = $this->get('request')->query->get('sort', 'a.id');
        $dir = $this->get('request')->query->get('dir', 'DESC');

        $next_dir= ($dir == 'DESC') ? 'ASC' : 'DESC';
        $arrow[$sort]= $next_dir=="DESC" ? 'icon-arrow-up' : 'icon-arrow-down' ;
        $page = $this->get('request')->query->get('page', 1); // Get custom page variable
        $query = $em->getRepository('ApplicationChangementsBundle:Docchangements')->getListBy($parameters);
        $adapter = new DoctrineORMAdapter($query);
        //$adapter->setDistinct(false);
        $pagerfanta = $this->mypager($adapter, 10);
        try {
            $pagerfanta->setCurrentPage($page);
            $q = $pagerfanta->getCurrentPageResults();
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }
        
         return $this->render('ApplicationChangementsBundle:Docchangements:indexfanta.html.twig', array(
                   'pagerfanta' => $pagerfanta,
                    'entities' => $q,
            'next_dir'=>$next_dir,
             'search_form' => $searchForm->createView(),
            'arrow'=>$arrow
        ));
         
        
    }
    
    
 public function indexAction(Request $request) {

        //  $entity = new Changements();
      $em = $this->getDoctrine()->getManager();
   /*      $json = $em->getRepository('ApplicationChangementsBundle:Docchangements')->findAjaxValue(array('nom' => 'ab'));
    exit(1);*/
        $parameters = array();
       
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'docchangements');
      
        $searchForm = $this->createForm(new DocchangementsFilterType());
     
        if ($request->getMethod() === 'POST' && $request->get('submit-filter') === "reset") {
            $session->remove('docchangementFilternew');
        } elseif ($request->getMethod() === 'POST' && $request->get('submit-filter') === "filter") {
            $alldatas = $request->request->all();
            $datas = $alldatas["docchangements_searchfilter"];
            $parameters = $datas;
            $session->set('docchangementFilternew', $datas);
            $searchForm->bind($datas);
        } else {
            if ($session->has('docchangementFilternew')) {
                $datas = $session->get('docchangementFilternew');
                $parameters = $datas;
                $searchForm->bind($datas);
            }
        }
          //  list($query,$count) = $em->getRepository('ApplicationChangementsBundle:Docchangements')->getListBy($parameters);
        /*
         * 
         * Ajout du order pour manytomany !!
         * 
         * 
         */
       $query = $em->getRepository('ApplicationChangementsBundle:Docchangements')->getListBy($parameters);
    $pagination = $this->createpaginator($query, 25);
    $total = $pagination->getTotalItemCount();
     //$pagination = $this->createpaginator($query, 15,$count);
        return $this->render('ApplicationChangementsBundle:Docchangements:index.html.twig', array(
                    'search_form' => $searchForm->createView(),
                    'pagination' => $pagination,
                    'total' => $total,
        ));
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function indexoAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationChangementsBundle:Docchangements')->findAll();

        return $this->render('ApplicationChangementsBundle:Docchangements:index.html.twig', array(
                    'entities' => $entities,
                ));
    }

    /**
     * Finds and displays a Docchangements entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationChangementsBundle:Docchangements')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docchangements entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationChangementsBundle:Docchangements:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new Docchangements entity.
     *
     */
    public function newAction() {
        $entity = new Docchangements();
        $form = $this->createForm(new DocchangementsType(), $entity);

        return $this->render('ApplicationChangementsBundle:Docchangements:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Creates a new Docchangements entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Docchangements();
        $form = $this->createForm(new DocchangementsType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
          // on ajoute cote changement
                 foreach ($entity->getIdchangement() AS $changement){
                     $changement->addPicture($entity);
                 }
            // on persite coté document
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('docchangements_show', array('id' => $entity->getId())));
        }
        return $this->render('ApplicationChangementsBundle:Docchangements:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to edit an existing Docchangements entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationChangementsBundle:Docchangements')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docchangements entity.');
        }

        $editForm = $this->createForm(new DocchangementsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationChangementsBundle:Docchangements:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing Docchangements entity.
     *
     */
    public function updateAction(Request $request, $id) {
      
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationChangementsBundle:Docchangements')->find($id);
        $current_changements = clone $entity->getIdchangement();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docchangements entity.');
        }
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
    }

        
    /**
     * Deletes a Docchangements entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationChangementsBundle:Docchangements')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Docchangements entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('docchangements'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

     public function downloadAction($id) {
         
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationChangementsBundle:Docchangements')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docchangements entity.');
        }
        
        $request = $this->get('request');
     //   $url='docchangements';
        $session = $request->getSession();
        $url=$session->get('buttonretour');
        if (!isset($url))
            $url='docchangements';    
     //   $path = $entity->getUploadRootDir();
        $filename=$entity->getPath();
        $realname=$entity->getOriginalFilename();
        // si existe pas, tant pis on prend le nom physique
        if (!isset($realname))
            $realname=$filename;
        $path = $this->get('kernel')->getRootDir() . "/../web/uploads/documents/";

        // Flush in "safe" mode to enforce an Exception if keys are not unique

         //if (!file_exists($path . $filename)) {
        if (!file_exists($path . $filename)) {
            $session->getFlashBag()->add('error', "Le fichier $filename n 'existe pas (code 1)");
             return $this->render('ApplicationChangementsBundle:errors:errorsxhtml.html.twig', array(
                    'message' => "Le fichier n'existe pas<br>Contacter l'administrateur"
                  ));
            //return $this->redirect($this->generateUrl('changements_fanta'));
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
        //$response->headers->set('Content-Length',filesize($filename));
        //$session = $this->getRequest()->getSession();
        $session->getFlashBag()->add('notice', "Le fichier $filename a ete téléchargé");

        $response->setContent($content);
        return $response;
    }
    
    
public function DocChangementsNomAjaxAction(Request $request) {
        $term = $request->get('term');
       // $alldatas = $request->request->all();
      //  print_r($alldatas);
        $em = $this->getDoctrine()->getManager();
        $json = $em->getRepository('ApplicationChangementsBundle:Docchangements')->findAjaxValue(array('nom' => $term));
        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    
        }
