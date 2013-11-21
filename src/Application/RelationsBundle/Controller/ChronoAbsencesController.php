<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\RelationsBundle\Entity\ChronoAbsences;
use Application\RelationsBundle\Form\ChronoAbsencesType;
use Application\CalendarBundle\Event\CalendarEvent;

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

        $entity = new ChronoAbsences();

        $form = $this->createForm(new ChronoAbsencesType(), $entity);

        return $this->render('ApplicationRelationsBundle:ChronoAbsences:calendrier.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function loadjqCalendarAbscencesAction(Request $request) {
        $startDatetime = new \DateTime();
        $startDatetime->setTimestamp($request->get('start'));
        $endDatetime = new \DateTime();
        $endDatetime->setTimestamp($request->get('end'));
        $calendar_events = new CalendarEvent($startDatetime, $endDatetime); //->getEvents();
        $manager = $this->get('chronoabsences.common.manager');
        $return_events = $manager->loadChronoCalendar($calendar_events);
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($return_events));
        return $response;
    }

    /* Update d'un record avec son id */

    public function AjaxFormOperationAbsenceAction(Request $request) {
        $data = array();
        $em = $this->getDoctrine()->getManager();

        $ret = array('IsSuccess' => false, 'Msg' => 'update unsuccess');
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $data = $request->get('chronoabsences');
            $id= $request->get('id');
           // echo "id=$id\n";
            $data['end'] = $data['dateFin'];
            $data['start'] = $data['dateDebut'];

            if ($id) {
                $manager = $this->get('chronoabsences.common.manager');
                $entity = $manager->loadAbsence($id);
            }
            else
                $entity = new ChronoAbsences();
            
            
            $form = $this->createForm(new ChronoAbsencesType(), $entity);
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $ret['IsSuccess'] = true;
                $ret['Msg'] = 'update success';
                $data['id'] = $entity->getId();
                $data['title'] = $entity->getUser() . ': ' . $entity->getNom();
                $data['className'] = "class1";
                $data['allDay'] = true;
            }
        }
        $ret['data'] = $data;
        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /* Update d'un record avec son id
     * 
     * Actions= resize, move event
     *  */

    public function updatejqCalendarAction(Request $request) {
        $data = array();
        $ret = array('IsSuccess' => false, 'Msg' => 'update unsuccess');
        $data['id'] = $request->get('id');
        if (!$data['id']) {
            $response = new Response(json_encode($ret));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {

            $manager = $this->get('chronoabsences.common.manager');
            $entity = $manager->loadAbsence($data['id']);


            $data['end'] = $request->get('end');
            $data['start'] = $request->get('start');
            $data['allDay'] = $request->get('allDay');
            $format = 'Y-m-d H:i:s';
            $d = \DateTime::createFromFormat($format, $data['start']);
            // $f = \DateTime::createFromFormat($format, $data['end']);
            if (!$data['end'] || $data['end'] == "") {
                // echo "here";  
                $f = \DateTime::createFromFormat($format, $data['start']);
            } else {
                //  echo "ok f";
                $f = \DateTime::createFromFormat($format, $data['end']);
            }
            $entity->setDateDebut($d);
            $entity->setDateFin($f);

            if ($data['allDay'])
                $entity->setAllDay($data['allDay']);
            $manager->saveAbsence($entity);
            $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('warning', "Enregistrement " . $data['id'] . " update successfull");
            $ret['IsSuccess'] = true;
            $ret['Msg'] = 'update success';
        }
        $ret['data'] = $data;
        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
