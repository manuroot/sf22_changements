<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\RelationsBundle\Entity\Applis;
use Application\RelationsBundle\Form\ApplisType;
use Application\RelationsBundle\Form\ApplisSimpleType;

/**
 * Applis controller.
 *
 */
class ApplisController extends Controller {

    /**
     * Lists all Applis entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ApplicationRelationsBundle:Applis')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        $pagination->setTemplate('ApplicationRelationsBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationRelationsBundle:Applis:index.html.twig', array(
                    'pagination' => $pagination,
                ));
    }

    /**
     * Finds and displays a Applis entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:Applis')->find($id);

        $projets = $entity->getIdprojets();


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Applis entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:Applis:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
                    'projets' => $projets,
                ));
    }

    /**
     * Displays a form to create a new Applis entity.
     *
     */
    public function newAction() {
        $entity = new Applis();
        $form = $this->createForm(new ApplisSimpleType(), $entity);

        return $this->render('ApplicationRelationsBundle:Applis:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Creates a new Applis entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Applis();
        $form = $this->createForm(new ApplisSimpleType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('applications_show', array('id' => $entity->getId())));
        }
        /*
          foreach ($entity->getIdprojets() AS $projet) {
          //$projet->addIdappli($entity);
          $projet->addIdappli($entity);
          //   var_dump($projet);
          } */
        return $this->render('ApplicationRelationsBundle:Applis:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to edit an existing Applis entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:Applis')->find($id);
        $projets = $entity->getIdprojets();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Applis entity.');
        }

        $editForm = $this->createForm(new ApplisType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:Applis:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'projets' => $projets,
                ));
    }

    /**
     * Edits an existing Applis entity.
     *
     */
    //public function updateAction(Request $request, $id) {
    public function updateAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:Applis')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Applis entity.');
        }
        $projets = $entity->getIdprojets();

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ApplisType(), $entity);

        $request = $this->getRequest();
        if ($request->getMethod() === 'POST') {
            $editForm->bind($request);

            if ($editForm->isValid()) {
            foreach ($entity->getIdprojets() AS $projet) {
                    //$projet->addIdappli($entity);
                    $projet->addIdappli($entity);
                    //   var_dump($projet);
                }
                //   $projets= $em->getRepository('ApplicationRelationsBundle:CertificatsProjet')->findByIdapplis($id);
                // $projets=$entity->getIdprojets();
                //    $projets->addIdappli($entity);
                //      $em->persist($projets);
                // $em->flush();
                // foreach ($projets as $projet) {
                //    $projets->addIdapplis($entity);
                //      $em->persist($projet);
                // $em->flush();
                // }
                /*   foreach ($entity as $list) {
                  $alist = $em->getRepository('ApplicationRelationsBundle:CertificatsProjet')
                  ->findOneById($list->getId());

                  $agent->addAgentList($aList);
                  $em->persist($agent);
                  $em->flush();

                  $aList->addAgent($agent);
                  $em->persist($aList);
                  $em->flush();
                  }
                 */



                $em->persist($entity);
                /*    $projets = $em->getRepository('ApplicationRelationsBundle:CertificatsProjet')->findByIdapplis($id);
                  if ($projets)
                  $projets->addIdappli($entity); */

                $em->flush();

                //  return $this->redirect($this->generateUrl('applications_edit', array('id' => $id)));
            }
        }

        return $this->render('ApplicationRelationsBundle:Applis:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'projets' => $projets,
                ));
    }

    /**
     * Deletes a Applis entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationRelationsBundle:Applis')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Applis entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('applications'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
