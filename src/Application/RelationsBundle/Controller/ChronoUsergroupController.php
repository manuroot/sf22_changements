<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\RelationsBundle\Entity\ChronoUsergroup;
use Application\RelationsBundle\Form\ChronoUsergroupType;

/**
 * ChronoUsergroup controller.
 *
 */
class ChronoUsergroupController extends Controller
{
    /**
     * Lists all ChronoUsergroup entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationRelationsBundle:ChronoUsergroup')->findAll();

        return $this->render('ApplicationRelationsBundle:ChronoUsergroup:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a ChronoUsergroup entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ChronoUsergroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ChronoUsergroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:ChronoUsergroup:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new ChronoUsergroup entity.
     *
     */
    public function newAction()
    {
        $entity = new ChronoUsergroup();
        $form   = $this->createForm(new ChronoUsergroupType(), $entity);

        return $this->render('ApplicationRelationsBundle:ChronoUsergroup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new ChronoUsergroup entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new ChronoUsergroup();
        $form = $this->createForm(new ChronoUsergroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('chronogroup_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationRelationsBundle:ChronoUsergroup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ChronoUsergroup entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ChronoUsergroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ChronoUsergroup entity.');
        }

        $editForm = $this->createForm(new ChronoUsergroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:ChronoUsergroup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing ChronoUsergroup entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:ChronoUsergroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ChronoUsergroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ChronoUsergroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('chronogroup_edit', array('id' => $id)));
        }

        return $this->render('ApplicationRelationsBundle:ChronoUsergroup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ChronoUsergroup entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationRelationsBundle:ChronoUsergroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ChronoUsergroup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('chronogroup'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
