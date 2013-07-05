<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\RelationsBundle\Entity\ServeursSites;
use Application\RelationsBundle\Form\ServeursSitesType;

/**
 * ServeursSites controller.
 *
 */
class ServeursSitesController extends Controller
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
         $entities = $em->getRepository('ApplicationRelationsBundle:ServeursSites')->findAll();
         //$querie = $em->getRepository('ApplicationRelationsBundle:Serveurs')->myfindAll();
       $pagination = $this->createpaginator($entities, 5);
       $count=$pagination->getTotalItemCount();
         return $this->render('ApplicationRelationsBundle:ServeursSites:index.html.twig', array(
            'pagination' => $pagination,
            'total'=>$count,
        ));
    }
    /**
     * Lists all ServeursSites entities.
     *
     */
   /* public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

      

        return $this->render('ApplicationRelationsBundle:ServeursSites:index.html.twig', array(
            'entities' => $entities,
        ));
    }*/
    /**
     * Creates a new ServeursSites entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new ServeursSites();
        $form = $this->createForm(new ServeursSitesType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('serveurs_sites_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationRelationsBundle:ServeursSites:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new ServeursSites entity.
     *
     */
    public function newAction()
    {
        $entity = new ServeursSites();
        $form   = $this->createForm(new ServeursSitesType(), $entity);

        return $this->render('ApplicationRelationsBundle:ServeursSites:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ServeursSites entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ServeursSites')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ServeursSites entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:ServeursSites:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing ServeursSites entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ServeursSites')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ServeursSites entity.');
        }

        $editForm = $this->createForm(new ServeursSitesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:ServeursSites:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing ServeursSites entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ServeursSites')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ServeursSites entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ServeursSitesType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('serveurs_sites_edit', array('id' => $id)));
        }

        return $this->render('ApplicationRelationsBundle:ServeursSites:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ServeursSites entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationRelationsBundle:ServeursSites')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ServeursSites entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('serveurs_sites'));
    }

    /**
     * Creates a form to delete a ServeursSites entity by id.
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
