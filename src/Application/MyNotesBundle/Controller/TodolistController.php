<?php

namespace Application\MyNotesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\MyNotesBundle\Entity\Notes;
use Application\MyNotesBundle\Form\NotesType;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
/*
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;*/
use Symfony\Component\HttpFoundation\Response;
use Application\MyNotesBundle\Entity\Todolist;
use Application\MyNotesBundle\Form\TodolistType;

/**
 * Todolist controller.
 *
 */
class TodolistController extends Controller
{
    /**
     * Lists all Todolist entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationMyNotesBundle:Todolist')->findAll();

        return $this->render('ApplicationMyNotesBundle:Todolist:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Todolist entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Todolist();
        $form = $this->createForm(new TodolistType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('todolist_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationMyNotesBundle:Todolist:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Todolist entity.
     *
     */
    public function newAction()
    {
        $entity = new Todolist();
        $form   = $this->createForm(new TodolistType(), $entity);

        return $this->render('ApplicationMyNotesBundle:Todolist:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Todolist entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationMyNotesBundle:Todolist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Todolist entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationMyNotesBundle:Todolist:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Todolist entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationMyNotesBundle:Todolist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Todolist entity.');
        }

        $editForm = $this->createForm(new TodolistType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationMyNotesBundle:Todolist:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Todolist entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationMyNotesBundle:Todolist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Todolist entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TodolistType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('todolist_edit', array('id' => $id)));
        }

        return $this->render('ApplicationMyNotesBundle:Todolist:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Todolist entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationMyNotesBundle:Todolist')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Todolist entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('todolist'));
    }

    /**
     * Creates a form to delete a Todolist entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
