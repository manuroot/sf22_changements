<?php

namespace Application\CalendarBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\CalendarBundle\Entity\CalendarEvenements;
use Application\CalendarBundle\Form\CalendarEvenementsType;

/**
 * CalendarEvenements controller.
 *
 */
class CalendarEvenementsController extends Controller
{

    /**
     * Lists all CalendarEvenements entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationCalendarBundle:CalendarEvenements')->findAll();

        return $this->render('ApplicationCalendarBundle:CalendarEvenements:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new CalendarEvenements entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CalendarEvenements();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('calendarcategories_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCalendarBundle:CalendarEvenements:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a CalendarEvenements entity.
    *
    * @param CalendarEvenements $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CalendarEvenements $entity)
    {
        $form = $this->createForm(new CalendarEvenementsType(), $entity, array(
            'action' => $this->generateUrl('calendarcategories_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CalendarEvenements entity.
     *
     */
    public function newAction()
    {
        $entity = new CalendarEvenements();
        $form   = $this->createCreateForm($entity);

        return $this->render('ApplicationCalendarBundle:CalendarEvenements:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CalendarEvenements entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarEvenements')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarEvenements entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCalendarBundle:CalendarEvenements:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CalendarEvenements entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarEvenements')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarEvenements entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCalendarBundle:CalendarEvenements:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CalendarEvenements entity.
    *
    * @param CalendarEvenements $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CalendarEvenements $entity)
    {
        $form = $this->createForm(new CalendarEvenementsType(), $entity, array(
            'action' => $this->generateUrl('calendarcategories_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

      //  $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CalendarEvenements entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarEvenements')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarEvenements entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        //$editForm = $this->createEditForm($entity);
        $editForm = $this->createForm(new CalendarEvenementsType(), $entity);
        $editForm->bind($request);
        //$editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('calendarcategories'));
        }
        //else{echo "not valide<br>";} 
return $this->render('ApplicationCalendarBundle:CalendarEvenements:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CalendarEvenements entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCalendarBundle:CalendarEvenements')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CalendarEvenements entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('calendarcategories'));
    }

    /**
     * Creates a form to delete a CalendarEvenements entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('calendarcategories_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
