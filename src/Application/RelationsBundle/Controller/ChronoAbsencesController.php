<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\RelationsBundle\Entity\ChronoAbsences;
use Application\RelationsBundle\Form\ChronoAbsencesType;

/**
 * ChronoAbsences controller.
 *
 */
class ChronoAbsencesController extends Controller
{

    
      /**
     * Lists all CertificatsProjet entities.
     * a completer
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'absences');

        // $entities = $em->getRepository('ApplicationRelationsBundle:Projet')->findAll();
        $entities = $em->getRepository('ApplicationRelationsBundle:ChronoAbsences')->myFindAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1)/* page number */, 15/* limit per page */
        );
        $pagination->setSortableTemplate('ApplicationRelationsBundle:pagination:sortable_link.html.twig');

        $pagination->setTemplate('ApplicationRelationsBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationRelationsBundle:ChronoAbsences:index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

    /**
     * Lists all ChronoAbsences entities.
     *
     */
    public function index1Action()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationRelationsBundle:ChronoAbsences')->findAll();

        return $this->render('ApplicationRelationsBundle:ChronoAbsences:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new ChronoAbsences entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ChronoAbsences();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('absences_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationRelationsBundle:ChronoAbsences:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a ChronoAbsences entity.
    *
    * @param ChronoAbsences $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(ChronoAbsences $entity)
    {
        $form = $this->createForm(new ChronoAbsencesType(), $entity, array(
            'action' => $this->generateUrl('absences_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ChronoAbsences entity.
     *
     */
    public function newAction()
    {
        $entity = new ChronoAbsences();
        $form   = $this->createCreateForm($entity);

        return $this->render('ApplicationRelationsBundle:ChronoAbsences:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ChronoAbsences entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ChronoAbsences')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ChronoAbsences entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:ChronoAbsences:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing ChronoAbsences entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ChronoAbsences')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ChronoAbsences entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:ChronoAbsences:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ChronoAbsences entity.
    *
    * @param ChronoAbsences $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ChronoAbsences $entity)
    {
        $form = $this->createForm(new ChronoAbsencesType(), $entity, array(
            'action' => $this->generateUrl('absences_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ChronoAbsences entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ChronoAbsences')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ChronoAbsences entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('absences_edit', array('id' => $id)));
        }

        return $this->render('ApplicationRelationsBundle:ChronoAbsences:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ChronoAbsences entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationRelationsBundle:ChronoAbsences')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ChronoAbsences entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('absences'));
    }

    /**
     * Creates a form to delete a ChronoAbsences entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('absences_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
