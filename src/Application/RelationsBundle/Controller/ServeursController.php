<?php

namespace Application\RelationsBundle\Controller;

use Application\RelationsBundle\Entity\Serveurs;
use Application\RelationsBundle\Form\ServeursType;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Symfony\Component\HttpFoundation\Session\Session;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
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
/**
 * Serveurs controller.
 *
 */
class ServeursController extends Controller
{

    /**
     * Lists all Serveurs entities.
     *
     */
 
      public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ApplicationRelationsBundle:Serveurs')->findAll();
         $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        $pagination->setTemplate('ApplicationRelationsBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationRelationsBundle:Serveurs:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
    
    /**
     * Creates a new Serveurs entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Serveurs();
        $form = $this->createForm(new ServeursType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('serveurs_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationRelationsBundle:Serveurs:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Serveurs entity.
     *
     */
    public function newAction()
    {
        $entity = new Serveurs();
        $form   = $this->createForm(new ServeursType(), $entity);

        return $this->render('ApplicationRelationsBundle:Serveurs:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Serveurs entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:Serveurs')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Serveurs entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:Serveurs:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Serveurs entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:Serveurs')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Serveurs entity.');
        }

        $editForm = $this->createForm(new ServeursType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:Serveurs:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Serveurs entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:Serveurs')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Serveurs entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ServeursType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('serveurs_edit', array('id' => $id)));
        }

        return $this->render('ApplicationRelationsBundle:Serveurs:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Serveurs entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationRelationsBundle:Serveurs')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Serveurs entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('serveurs'));
    }

    /**
     * Creates a form to delete a Serveurs entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
