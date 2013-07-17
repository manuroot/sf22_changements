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

/**
 * Docchangements controller.
 *
 */
class DocchangementsController extends Controller {

    /**
     * Lists all Docchangements entities.
     *
     */
    public function indexAction() {
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
             // list($fic,$ext)=$entity->getFilename();
            //    echo "ext=$ext $fic<br>";exit(1);
    
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
        /*  $postData = $request->request->get('doc');
        //$data = $editForm->getData();
        var_dump($postData);
              exit(1); */
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
        if (!isset($realname))
            $realname=$filename;
        $path = $this->get('kernel')->getRootDir() . "/../web/uploads/documents/";

        // Flush in "safe" mode to enforce an Exception if keys are not unique

         //if (!file_exists($path . $filename)) {
        if (!file_exists($path . $filename)) {
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
        //$response->headers->set('Content-Length',filesize($filename));
        //$session = $this->getRequest()->getSession();
        $session->getFlashBag()->add('notice', "Le fichier $filename a ete téléchargé");

        $response->setContent($content);
        return $response;
    }
}
