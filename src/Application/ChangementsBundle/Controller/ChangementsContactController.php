<?php

namespace Application\ChangementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Application\ChangementsBundle\Entity\ChangementsContact;
use Application\ChangementsBundle\Form\ChangementsContactType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * ChangementsContact controller.
 *
 */
class ChangementsContactController extends Controller {

    /**
     * Lists all ChangementsContact entities.
     *
     */
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('ApplicationChangementsBundle:ChangementsContact')->myFindAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 20/* limit per page */
        );
        $pagination->setSortableTemplate('ApplicationChangementsBundle:pagination:sortable_link.html.twig');
        $pagination->setTemplate('ApplicationChangementsBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationChangementsBundle:ChangementsContact:index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

    /**
     * Finds and displays a ChangementsContact entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationChangementsBundle:ChangementsContact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ChangementsContact entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('ApplicationChangementsBundle:ChangementsContact:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new ChangementsContact entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function newAction() {
        $entity = new ChangementsContact();
        $form = $this->createForm(new ChangementsContactType(), $entity);

        return $this->render('ApplicationChangementsBundle:ChangementsContact:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new ChangementsContact entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     */
    public function createAction(Request $request) {
        $entity = new ChangementsContact();
        $form = $this->createForm(new ChangementsContactType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $session = $this->getRequest()->getSession();
            // ajoute des messages flash
            $id = $entity->getId();
            $session->getFlashBag()->add('warning', "Enregistrement $id ajouté avec succès");
            return $this->redirect($this->generateUrl('changements_contact'));
            //return $this->redirect($this->generateUrl('chronouser_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationChangementsBundle:ChangementsContact:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ChangementsContact entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationChangementsBundle:ChangementsContact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ChangementsContact entity.');
        }

        $editForm = $this->createForm(new ChangementsContactType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationChangementsBundle:ChangementsContact:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing ChangementsContact entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationChangementsBundle:ChangementsContact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ChangementsContact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ChangementsContactType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $session = $this->getRequest()->getSession();
            // ajoute des messages flash
            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");

            //return $this->redirect($this->generateUrl('chronouser_edit', array('id' => $id)));
            return $this->redirect($this->generateUrl('changements_contact'));
        }

        return $this->render('ApplicationChangementsBundle:ChangementsContact:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ChangementsContact entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationChangementsBundle:ChangementsContact')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ChangementsContact entity.');
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
