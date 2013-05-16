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
        //$pagination->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
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

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docchangements entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DocchangementsType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
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

}
