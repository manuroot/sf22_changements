<?php

namespace Application\MyNotesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\MyNotesBundle\Entity\NotesColor;
use Application\MyNotesBundle\Form\NotesColorType;

/**
 * NotesColor controller.
 *
 */
class NotesColorController extends Controller
{
    /**
     * Lists all NotesColor entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationMyNotesBundle:NotesColor')->findAll();

        return $this->render('ApplicationMyNotesBundle:NotesColor:index.html.twig', array(
            'entities' => $entities,
        ));
    }


    
    /**
     * Finds and displays a NotesColor entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationMyNotesBundle:NotesColor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NotesColor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationMyNotesBundle:NotesColor:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new NotesColor entity.
     *
     */
    public function newAction()
    {
        $entity = new NotesColor();
        $form   = $this->createForm(new NotesColorType(), $entity);

        return $this->render('ApplicationMyNotesBundle:NotesColor:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new NotesColor entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new NotesColor();
        $form = $this->createForm(new NotesColorType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('colornotes_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationMyNotesBundle:NotesColor:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing NotesColor entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationMyNotesBundle:NotesColor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NotesColor entity.');
        }

        $editForm = $this->createForm(new NotesColorType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationMyNotesBundle:NotesColor:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing NotesColor entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationMyNotesBundle:NotesColor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NotesColor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new NotesColorType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('colornotes_edit', array('id' => $id)));
        }

        return $this->render('ApplicationMyNotesBundle:NotesColor:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a NotesColor entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationMyNotesBundle:NotesColor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find NotesColor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('colornotes'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
