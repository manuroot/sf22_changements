<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\RelationsBundle\Entity\Environnements;
use Application\RelationsBundle\Form\EnvironnementsType;

/**
 * Environnements controller.
 *
 */
class EnvironnementsController extends Controller
{
    /**
     * Lists all Environnements entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationRelationsBundle:Environnements')->findAll();

        return $this->render('ApplicationRelationsBundle:Environnements:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Environnements entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:Environnements')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Environnements entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:Environnements:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Environnements entity.
     *
     */
    public function newAction()
    {
        $entity = new Environnements();
        $form   = $this->createForm(new EnvironnementsType(), $entity);

        return $this->render('ApplicationRelationsBundle:Environnements:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Environnements entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Environnements();
        $form = $this->createForm(new EnvironnementsType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('environnement_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationRelationsBundle:Environnements:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Environnements entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:Environnements')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Environnements entity.');
        }

        $editForm = $this->createForm(new EnvironnementsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:Environnements:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Environnements entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:Environnements')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Environnements entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EnvironnementsType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('environnement_edit', array('id' => $id)));
        }

        return $this->render('ApplicationRelationsBundle:Environnements:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Environnements entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationRelationsBundle:Environnements')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Environnements entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('environnement'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
