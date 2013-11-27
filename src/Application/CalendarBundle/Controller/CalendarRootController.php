<?php

namespace Application\CalendarBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\CalendarBundle\Entity\CalendarRoot;
use Application\CalendarBundle\Form\CalendarRootType;

/**
 * CalendarRoot controller.
 *
 */
class CalendarRootController extends Controller
{

    /**
     * Lists all CalendarRoot entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->findAll();

        return $this->render('ApplicationCalendarBundle:CalendarRoot:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new CalendarRoot entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CalendarRoot();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('calendarroot_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCalendarBundle:CalendarRoot:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a CalendarRoot entity.
    *
    * @param CalendarRoot $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CalendarRoot $entity)
    {
        $form = $this->createForm(new CalendarRootType(), $entity, array(
            'action' => $this->generateUrl('calendarroot_create'),
            'method' => 'POST',
        ));

       // $form->add('submit', 'submit', array('label' => 'Create'))                ;

        return $form;
    }

    /**
     * Displays a form to create a new CalendarRoot entity.
     *
     */
    public function newAction()
    {
        $entity = new CalendarRoot();
        $form   = $this->createCreateForm($entity);

        return $this->render('ApplicationCalendarBundle:CalendarRoot:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CalendarRoot entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarRoot entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCalendarBundle:CalendarRoot:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CalendarRoot entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarRoot entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCalendarBundle:CalendarRoot:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CalendarRoot entity.
    *
    * @param CalendarRoot $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CalendarRoot $entity)
    {
        $form = $this->createForm(new CalendarRootType(), $entity, array(
            'action' => $this->generateUrl('calendarroot_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

      //  $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CalendarRoot entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarRoot entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('calendarroot'));
        }
        else {echo "not valid";}

        return $this->render('ApplicationCalendarBundle:CalendarRoot:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CalendarRoot entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CalendarRoot entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('calendarroot'));
    }

    /**
     * Creates a form to delete a CalendarRoot entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('calendarroot_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
