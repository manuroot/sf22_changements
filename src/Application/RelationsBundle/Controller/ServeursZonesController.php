<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\RelationsBundle\Entity\ServeursZones;
use Application\RelationsBundle\Form\ServeursZonesType;

/**
 * ServeursZones controller.
 *
 */
class ServeursZonesController extends Controller
{

      /*==================================================================
      * 
     *  CREATION DU PAGINATOR
     * 
      =================================================================== */

    private function createpaginator($query, $num_perpage = 5) {

        $paginator = $this->get('knp_paginator');
        //$paginator->setUseOutputWalkers(true);
        $pagename = 'page'; // Set custom page variable name
        $page = $this->get('request')->query->get($pagename, 1); // Get custom page variable

        $pagination = $paginator->paginate(
                $query, $page, $num_perpage, array('pageParameterName' => $pagename,
            "sortDirectionParameterName" => "dir",
            'sortFieldParameterName' => "sort")
        );
        $pagination->setTemplate('ApplicationRelationsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        $pagination->setSortableTemplate('ApplicationRelationsBundle:pagination:sortable_link.html.twig');
        return $pagination;
    }
    /**
     * Lists all Serveurs entities.
     *
     */
 
      public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
         $entities = $em->getRepository('ApplicationRelationsBundle:ServeursZones')->findAll();
         //$querie = $em->getRepository('ApplicationRelationsBundle:Serveurs')->myfindAll();
       $pagination = $this->createpaginator($entities, 5);
       $count=$pagination->getTotalItemCount();
         return $this->render('ApplicationRelationsBundle:ServeursZones:index.html.twig', array(
            'pagination' => $pagination,
            'total'=>$count,
        ));
    }
  
    /**
     * Creates a new ServeursZones entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new ServeursZones();
        $form = $this->createForm(new ServeursZonesType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('serveurs_zones_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationRelationsBundle:ServeursZones:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new ServeursZones entity.
     *
     */
    public function newAction()
    {
        $entity = new ServeursZones();
        $form   = $this->createForm(new ServeursZonesType(), $entity);

        return $this->render('ApplicationRelationsBundle:ServeursZones:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ServeursZones entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ServeursZones')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ServeursZones entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:ServeursZones:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing ServeursZones entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ServeursZones')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ServeursZones entity.');
        }

        $editForm = $this->createForm(new ServeursZonesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:ServeursZones:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing ServeursZones entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ServeursZones')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ServeursZones entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ServeursZonesType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('serveurs_zones_edit', array('id' => $id)));
        }

        return $this->render('ApplicationRelationsBundle:ServeursZones:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ServeursZones entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationRelationsBundle:ServeursZones')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ServeursZones entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('serveurs_zones'));
    }

    /**
     * Creates a form to delete a ServeursZones entity by id.
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
