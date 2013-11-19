<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\RelationsBundle\Entity\ChronoAbsences;
use Application\RelationsBundle\Form\ChronoAbsencesType;

/**
 * ChronoAbsences controller.
 *
 */
class ChronoAbsencesController extends Controller {

    /**
     * Lists all CertificatsProjet entities.
     * a completer
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'absences');

        // $entities = $em->getRepository('ApplicationRelationsBundle:Projet')->findAll();
        $entities = $em->getRepository('ApplicationRelationsBundle:ChronoAbsences')->myFindAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1)/* page number */, 15/* limit per page */
        );
        $pagination->setSortableTemplate('ApplicationRelationsBundle:pagination:sortable_link.html.twig');

        $pagination->setTemplate('ApplicationRelationsBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationRelationsBundle:ChronoAbsences:index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

 

    /**
     * Creates a new ChronoAbsences entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new ChronoAbsences();
        $form = $this->createForm(new ChronoAbsencesType(), $entity);
         $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('absences'));
        }
        return $this->render('ApplicationRelationsBundle:ChronoAbsences:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    
    /**
     * Displays a form to create a new ChronoAbsences entity.
     *
     */
    public function newAction() {
        $entity = new ChronoAbsences();
        $form = $this->createForm(new ChronoAbsencesType(), $entity);
        return $this->render('ApplicationRelationsBundle:ChronoAbsences:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ChronoAbsences entity.
     *
     */
    public function editAction($id) {
        $entity = $this->get('chronoabsences.common.manager')->loadAbsence($id);
        $editForm = $this->createForm(new ChronoAbsencesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('ApplicationRelationsBundle:ChronoAbsences:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

 
    /**
     * Edits an existing ChronoAbsences entity.
     *
     */
    public function updateAction(Request $request, $id) {

        $manager = $this->get('chronoabsences.common.manager');
        $entity = $manager->loadAbsence($id);
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ChronoAbsencesType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $manager->saveAbsence($entity);
            $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");
            return $this->redirect($this->generateUrl('absences'));
        }

        // sinon:
        $session->getFlashBag()->add('error', "Enregistrement erreur");
        return $this->render('ApplicationRelationsBundle:ChronoAbsences:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ChronoAbsences entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);
        if ($form->isValid()) {
            $this->get('chronoabsences.common.manager')->deleteAbsence($id);
            return $this->redirect($this->generateUrl('absences'));
        }
        return $this->redirect($this->generateUrl('absences'));
    }

    /**
     * Creates a form to delete a ChronoAbsences entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('absences_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }
    
    
    
    
    
    public function calendrierAbsencesAction(Request $request) {
           $em = $this->getDoctrine()->getManager();
  //$entity_evements = $em->getRepository('ApplicationChangementsBundle:ChangementsStatus')->findall();
   
        return $this->render('ApplicationRelationsBundle:ChronoAbsences:calendrier.html.twig', array(
        ));
    }

  
    

    public function loadjqCalendarAbscencesAction(Request $request) {
        $startDatetime = new \DateTime();
        $startDatetime->setTimestamp($request->get('start'));
        $endDatetime = new \DateTime();
        $endDatetime->setTimestamp($request->get('end'));
        
        // TODO
        // A adapter
        $events = $this->container->get('event_dispatcher')->dispatch(CalendarEvent::CONFIGUREA, new CalendarEvent($startDatetime, $endDatetime))->getEvents();
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $return_events = array();
        foreach ($events as $event) {
            $return_events[] = $event->toArray();
        }
        $response->setContent(json_encode($return_events));
        return $response;
    }


}
