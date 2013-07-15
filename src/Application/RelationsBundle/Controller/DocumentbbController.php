<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\RelationsBundle\Entity\Documentbb;
use Application\RelationsBundle\Form\DocumentbbType;

/**
 * Documentbb controller.
 *
 */
class DocumentbbController extends Controller {
    /*     * 
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

    public function indexAction() {
        //$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'serveurs');
        $em = $this->getDoctrine()->getManager();
        $entites = $em->getRepository('ApplicationRelationsBundle:Documentbb')->myFindAll();
        $pagination = $this->createpaginator($entites, 15);
        $count = $pagination->getTotalItemCount();
        return $this->render('ApplicationRelationsBundle:Documentbb:index.html.twig', array(
                    'pagination' => $pagination,
                    'total' => $count,
        ));
    }

    /**
     * Creates a new Documentbb entity.
     *
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = new Documentbb();
        $form = $this->createForm(new DocumentbbType(), $entity);
        $form->bind($request);
        //  exit(1);
        if ($form->isValid()) {
            // on ajoute le document aux changements (cote changements)
            foreach ($entity->getIdprojet() AS $projet) {
                $projet->addPicture($entity);
            }
            // on persite coté document
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('projets_documents_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationRelationsBundle:Documentbb:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Documentbb entity.
     *
     */
    public function newAction() {
        $entity = new Documentbb();
        $form = $this->createForm(new DocumentbbType(), $entity);
        return $this->render('ApplicationRelationsBundle:Documentbb:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Documentbb entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationRelationsBundle:Documentbb')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Documentbb entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('ApplicationRelationsBundle:Documentbb:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Documentbb entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationRelationsBundle:Documentbb')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Erreur edit: Unable to find Documentbb entity.');
        }
        $editForm = $this->createForm(new DocumentbbType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('ApplicationRelationsBundle:Documentbb:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Documentbb entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationRelationsBundle:Documentbb')->find($id);
        $current_projets = clone $entity->getIdprojet();
        if (!$entity) {
            throw $this->createNotFoundException('Error: Unable to find Documentbb entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DocumentbbType(), $entity);
        $editForm->bind($request);
        if ($editForm->isValid()) {
            foreach ($current_projets as $projet) {
                $projet->getPicture()->removeElement($entity);
                $em->persist($projet);
            }
            // on ajoute cote projet
            foreach ($entity->getIdprojet() AS $projet) {
                $projet->addPicture($entity);
            }
            $em->persist($entity);
            $em->flush();
            $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");
            return $this->redirect($this->generateUrl('projets_documents_edit', array('id' => $id)));
        }

        return $this->render('ApplicationRelationsBundle:Documentbb:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Documentbb entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationRelationsBundle:Documentbb')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Documentbb entity.');
            }
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('projets_documents'));
    }

    /**
     * Creates a form to delete a Documentbb entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
