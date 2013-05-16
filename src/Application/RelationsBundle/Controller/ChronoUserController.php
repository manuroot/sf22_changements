<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Application\RelationsBundle\Entity\ChronoUser;
use Application\RelationsBundle\Form\ChronoUserType;

/**
 * ChronoUser controller.
 *
 */
class ChronoUserController extends Controller {

    /**
     * Lists all ChronoUser entities.
     *
     */
    public function indexAction() {


        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('ApplicationRelationsBundle:ChronoUser')->myFindAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 15/* limit per page */
        );
        $pagination->setTemplate('ApplicationRelationsBundle:pagination:sliding.html.twig');
        //$pagination->setTemplate('ApplicationMyNotesBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationRelationsBundle:ChronoUser:index.html.twig', array(
                    'pagination' => $pagination,
                ));
    }

    /**
     * Finds and displays a ChronoUser entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ChronoUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ChronoUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:ChronoUser:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new ChronoUser entity.
     *
     */
    public function newAction() {
        $entity = new ChronoUser();
        $form = $this->createForm(new ChronoUserType(), $entity);

        return $this->render('ApplicationRelationsBundle:ChronoUser:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Creates a new ChronoUser entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new ChronoUser();
        $form = $this->createForm(new ChronoUserType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $session = $this->getRequest()->getSession();
            // ajoute des messages flash
            $id = $entity->getId();
            $session->getFlashBag()->add('warning', "Enregistrement $id ajouté avec succès");
            return $this->redirect($this->generateUrl('chronouser'));
            //return $this->redirect($this->generateUrl('chronouser_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationRelationsBundle:ChronoUser:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to edit an existing ChronoUser entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ChronoUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ChronoUser entity.');
        }

        $editForm = $this->createForm(new ChronoUserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:ChronoUser:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing ChronoUser entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ChronoUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ChronoUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ChronoUserType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $session = $this->getRequest()->getSession();
            // ajoute des messages flash
            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");

            //return $this->redirect($this->generateUrl('chronouser_edit', array('id' => $id)));
            return $this->redirect($this->generateUrl('chronouser'));
        }

        return $this->render('ApplicationRelationsBundle:ChronoUser:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a ChronoUser entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationRelationsBundle:ChronoUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ChronoUser entity.');
            }

            $em->remove($entity);
            $em->flush();
               $session = $this->getRequest()->getSession();
            // ajoute des messages flash
            $session->getFlashBag()->add('warning', "Enregistrement $id supprimé avec succès");
       
        }

        return $this->redirect($this->generateUrl('chronouser'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
