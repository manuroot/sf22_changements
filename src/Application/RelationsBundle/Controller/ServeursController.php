<?php

namespace Application\RelationsBundle\Controller;

use Application\RelationsBundle\Entity\Serveurs;
use Application\RelationsBundle\Form\ServeursType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\RelationsBundle\Form\ServeursFiltresType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/*
  use Pagerfanta\Pagerfanta;
  use Pagerfanta\Adapter\DoctrineORMAdapter;
  use Pagerfanta\Exception\NotValidCurrentPageException;
 * 
 */
/*
  use APY\DataGridBundle\Grid\Source\Entity;
  use APY\DataGridBundle\Grid\Grid;
  use APY\DataGridBundle\Grid\Column\ActionsColumn;
  use APY\DataGridBundle\Grid\Action\MassAction;
  use APY\DataGridBundle\Grid\Action\DeleteMassAction;
  use APY\DataGridBundle\Grid\Action\RowAction;
  use APY\DataGridBundle\Grid\Column\TextColumn;
  use APY\DataGridBundle\Grid\Column\DateColumn;
  use Symfony\Component\Security\Core\Exception\AccessDeniedException;
  use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
  use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
  use Symfony\Component\Security\Acl\Permission\MaskBuilder;
  use JMS\SecurityExtraBundle\Annotation\Secure; */

/**
 * Serveurs controller.
 *
 */
class ServeursController extends Controller {
    /* ==================================================================
     * 
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

    /**
     * Lists all Serveurs entities.
     *
     */
    public function indexAction() {
        $message = "";
        //$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'serveurs');
        list($filterForm, $queryBuilder, $message) = $this->filter();
        if ($message)
            $session->getFlashBag()->add('warning', "$message");
        $pagination = $this->createpaginator($queryBuilder, 15);
        $count = $pagination->getTotalItemCount();
        return $this->render('ApplicationRelationsBundle:Serveurs:index.html.twig', array(
                    'pagination' => $pagination,
                    'total' => $count,
                    'search_form' => $filterForm->createView(),
        ));
    }

    public function indexoldAction() {
        $em = $this->getDoctrine()->getManager();
        $querie = $em->getRepository('ApplicationRelationsBundle:Serveurs')->myfindAll();
        $pagination = $this->createpaginator($querie, 15);
        $count = $pagination->getTotalItemCount();
        return $this->render('ApplicationRelationsBundle:Serveurs:index.html.twig', array(
                    'pagination' => $pagination,
                    'total' => $count,
        ));
    }

    /**
     * Creates a new Serveurs entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Serveurs();
        $form = $this->createForm(new ServeursType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $session = $this->getRequest()->getSession();
            // ajoute des messages flash
            $id = $entity->getId();
            $session->getFlashBag()->add('warning', "Enregistrement $id ajouté avec succès");
            return $this->redirect($this->generateUrl('serveurs_show', array('id' => $id)));
        }
        return $this->render('ApplicationRelationsBundle:Serveurs:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Serveurs entity.
     *
     * @Secure(roles="ROLE_USER")
     */
    public function newAction() {
        $entity = new Serveurs();
        $form = $this->createForm(new ServeursType(), $entity);
        return $this->render('ApplicationRelationsBundle:Serveurs:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Serveurs entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationRelationsBundle:Serveurs')->myFindAll($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Serveurs entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('ApplicationRelationsBundle:Serveurs:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Serveurs entity.
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationRelationsBundle:Serveurs')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Serveurs entity.');
        }
        $editForm = $this->createForm(new ServeursType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('ApplicationRelationsBundle:Serveurs:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Serveurs entity.
     *
     * @Secure(roles="ROLE_USER")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationRelationsBundle:Serveurs')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Serveurs entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ServeursType(), $entity);
        $editForm->bind($request);
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $session = $this->getRequest()->getSession();
            // ajoute des messages flash
            $id = $entity->getId();
            $session->getFlashBag()->add('warning', "Enregistrement $id modifié avec succès");
            return $this->redirect($this->generateUrl('serveurs_edit', array('id' => $id)));
        }
        return $this->render('ApplicationRelationsBundle:Serveurs:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Serveurs entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationRelationsBundle:Serveurs')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Serveurs entity.');
            }
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('serveurs'));
    }

    /**
     * Creates a form to delete a Serveurs entity by id.
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

    public function update_serveurs_statusAction() {
        $request = $this->get('request');
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $id = $request->request->get('id');
            $em = $this->getDoctrine()->getManager();
            $retour = "ko";
            if ($id) {
                $entity = $em->getRepository('ApplicationRelationsBundle:Serveurs')->find($id);
                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Cert entity.');
                }
                $retour = "nok";
                $id_warning = $entity->getWarning();
                if ($id_warning) {
                    $entity->setWarning(false);
                    $em->persist($entity);
                    $em->flush();
                    $retour = "a--$id_warning-- ok";
                } else {
                    $entity->setWarning(true);
                    $em->persist($entity);
                    $em->flush();
                    $retour = "b--$id_warning-- nok";
                }
            }
            $array = array('mystatus' => $retour);
            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /* ==================================================================
     * 
     *  FILTRES FORM
     * 
     * =================================================================== */

    protected function filter() {
        //  $message = "filter datas";
        $message = "";
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new ServeursFiltresType());
        $filterBuilder = $em->getRepository('ApplicationRelationsBundle:Serveurs')->myFindAll();
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "reset") {
            //  $message = "reset filtres";
            $session->remove('serveursFilter');
            $query = $filterBuilder;
            return array($filterForm, $query, $message);
        }

        // datas filter
        if ($request->getMethod() == 'POST' && $request->get('submit-filter') == "filter") {
            $alldatas = $request->request->all();
            $datas = $alldatas["serveurs_filter"];
            $filterForm->bind($datas);
            if ($filterForm->isValid()) {
                // $message .= " - filtre valide";
                $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $filterBuilder);
                $session->set('serveursFilter', $datas);
            } else {
                $query = $filterBuilder;
            }
            return array($filterForm, $query, $message);
        } else {
            if ($session->has('serveursFilter')) {

                $datas = $session->get('serveursFilter');
                $filterForm->bind($datas);
                $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $filterBuilder);
            }
            // ou pas
            else {
                // $message = "pas de session";
                $query = $filterBuilder;
            }
            return array($filterForm, $query, $message);
        }
    }

}
