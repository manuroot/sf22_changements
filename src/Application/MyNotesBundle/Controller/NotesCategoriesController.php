<?php

namespace Application\MyNotesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\MyNotesBundle\Entity\NotesCategories;
use Application\MyNotesBundle\Form\NotesCategoriesType;

/**
 * NotesCategories controller.
 *
 */
class NotesCategoriesController extends Controller
{
    /**
     * Lists all NotesCategories entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationMyNotesBundle:NotesCategories')->findAll();

        return $this->render('ApplicationMyNotesBundle:NotesCategories:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a NotesCategories entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationMyNotesBundle:NotesCategories')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NotesCategories entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationMyNotesBundle:NotesCategories:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new NotesCategories entity.
     *
     */
    public function newAction()
    {
        $entity = new NotesCategories();
        $form   = $this->createForm(new NotesCategoriesType(), $entity);

        return $this->render('ApplicationMyNotesBundle:NotesCategories:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new NotesCategories entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new NotesCategories();
        $form = $this->createForm(new NotesCategoriesType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('categoriesnotes_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationMyNotesBundle:NotesCategories:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing NotesCategories entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationMyNotesBundle:NotesCategories')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NotesCategories entity.');
        }

        $editForm = $this->createForm(new NotesCategoriesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationMyNotesBundle:NotesCategories:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing NotesCategories entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationMyNotesBundle:NotesCategories')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NotesCategories entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new NotesCategoriesType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('categoriesnotes_edit', array('id' => $id)));
        }

        return $this->render('ApplicationMyNotesBundle:NotesCategories:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a NotesCategories entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationMyNotesBundle:NotesCategories')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find NotesCategories entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('categoriesnotes'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
