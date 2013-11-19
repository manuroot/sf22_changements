<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
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

    public function updatejqCalendarAction(Request $request) {

        $data = array();
        $em = $this->getDoctrine()->getManager();
        $ret = array();
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $data['id'] = $request->get('id');

            $action = $request->get('action');
            if (isset($action) && $action == "delete") {
                $form = $this->createDeleteForm($id);
                $form->bind($request);
                if ($form->isValid()) {
                    $this->get('chronoabsences.common.manager')->deleteAbsence($id);
                    $ret['id'] = $data['id'];
                    $ret['status'] = 'removed';
                    $response = new Response(\json_encode($ret));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
            }

            // sinon on continue
            $data['end'] = $request->get('end');
            $data['start'] = $request->get('start');
            $format = 'Y-m-d H:i:s';
            $d = \DateTime::createFromFormat($format, $data['start']);
            if (!$data['end'] || $data['end'] == "") {
                $f = \DateTime::createFromFormat($format, $data['start']);
            } else {
                $f = \DateTime::createFromFormat($format, $data['end']);
            }
            /* ========================================
             * 
             *             NEW EVENT (absence)
             * 
              ========================================= */
            if (!$data['id']) {
                $data['title'] = $request->get('title');
                $entity = new ChronoAbsences($data['title'], $d, $f);
                
                $entity->setUrl('4564');
                $bgcolor = $request->get('background-color', '#000000');
                $classcss = $request->get('className', 'class1');
                $description = $request->get('description', 'Pas de description');
                $allday = $request->get('allDay');
                $fgcolor = $request->get('background-color', '#FFFFFF');
                $entity->setBgColor($bgcolor); //set the background color of the event's label
                /* if ($allday == 'true')
                  $entity->setAllDay(true);
                  else
                  $entity->setAllDay(false); */
                $entity->setAllDay($allday);
                $data['allDay'] = (boolean) $allday;
                $entity->setCssClass($classcss);
                $data['className'] = $classcss;

                $entity->setDescription($description);
                $entity->setFgColor($fgcolor); //set the foreground color of the event's label
                $em->persist($entity);
                $em->flush();
                $ret['IsSuccess'] = true;
                $ret['Msg'] = 'update success';
                $data['id'] = $entity->getId();
            }
            /* ========================================
             * 
             *             UPDATE EVENT
             * 
              ========================================= */ else {
                $entity = $em->getRepository('ApplicationCalendarBundle:AdesignCalendar')->find($data['id']);
                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find ChangementsContact entity.');
                }
                // $this->getRequest()->query->all(); 
                //to get all GET params and 
                //$this->getRequest()->request->all(); to get all POST params.
                $entity->setStartDatetime($d);
                $entity->setEndDatetime($f);
                //$all= $request->all();
                $title = $request->get('title');
                $description = $request->get('description');
                $classcss = $request->get('className', 'class1');
                /* fields optionnels dans le post */
                if ($description) {
                    $entity->setDescription($description);
                }
                $allday = $request->get('allDay');
                $entity->setAllDay($allday);
                if ($title)
                    $entity->setTitle($title);
                if ($classcss) {

                    $data['className'] = $classcss;
                    $entity->setCssClass($classcss);
                }
                $em->persist($entity);
                $em->flush();
                //  $data['allDay']=(boolean)$allday;
                $ret['IsSuccess'] = true;
                $ret['Msg'] = 'update success';
            }
            $ret['data'] = $data;
        }

        $response = new Response(json_encode($ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
