<?php

namespace Application\CalendarBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\CalendarBundle\Entity\CalendarCategories;
use Application\CalendarBundle\Form\CalendarCategoriesType;

/**
 * CalendarCategories controller.
 *
 */
class CalendarCategoriesController extends Controller {
    /* ====================================================================
     *
     * SECURITY
     *
      =================================================================== */

    private function getuserid() {

        $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $user_id = $user->getId();
        } else {
            $user_id = 0;
        }
        return $user_id;
    }

    /* ====================================================================
     *
     * ROOT CALENDAR
     *
      =================================================================== */

    protected function getCalendarRoot() {
        $request = $this->getRequest();
        $session = $request->getSession();
        if (!$session->has('calendar_id')) {
            $session->set('calendar_id', '1');
        }
        $id_cal = $session->get('calendar_id');
        return $id_cal;
    }

    /*     * ================================================================
     * Lists all CalendarCategories entities.
     *
      =============================================================== */

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $id_cal = $this->getCalendarRoot();
        $rootcalendar = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->findOneById($id_cal);
        $entities = $em->getRepository('ApplicationCalendarBundle:CalendarCategories')->myFindAll($id_cal);
        return $this->render('ApplicationCalendarBundle:CalendarCategories:index.html.twig', array(
                    'entities' => $entities,
                    'root_calendar' => $rootcalendar
        ));
    }

    /*     * =====================================================================
     * Creates a new CalendarCategories entity.
     *
      ====================================================================== */

    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = new CalendarCategories();
        $id_cal = $this->getCalendarRoot();
        $rootcalendar = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->find($id_cal);
        if ($rootcalendar) {
            $entity->setRootcalendar($rootcalendar);
        }
        $form = $this->createCreateForm($entity);
        $form->bind($request);
        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('calendarcategories'));
        }
        return $this->render('ApplicationCalendarBundle:CalendarCategories:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a CalendarCategories entity.
     *
     * @param CalendarCategories $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CalendarCategories $entity) {
        $form = $this->createForm(new CalendarCategoriesType(), $entity, array(
            'action' => $this->generateUrl('calendarcategories_create'),
            'method' => 'POST',
        ));
        return $form;
    }

    /**
     * Displays a form to create a new CalendarCategories entity.
     *
     */
    public function newAction() {
        $entity = new CalendarCategories();
        $form = $this->createCreateForm($entity);

        return $this->render('ApplicationCalendarBundle:CalendarCategories:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CalendarCategories entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarCategories')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarCategories entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('ApplicationCalendarBundle:CalendarCategories:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing CalendarCategories entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarCategories')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarCategories entity.');
        }
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCalendarBundle:CalendarCategories:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CalendarCategories entity.
     *
     * @param CalendarCategories $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CalendarCategories $entity) {
        $form = $this->createForm(new CalendarCategoriesType(), $entity, array(
            'action' => $this->generateUrl('calendarcategories_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        //  $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing CalendarCategories entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarCategories')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarCategories entity.');
        }

        /* if ($request->getMethod() == 'POST') {
          //  $data = $request->request->all();
          //   var_dump($data);
          } */
        /* foreach($request->request->all() as $req){
          var_dump($req);
          } */
        $deleteForm = $this->createDeleteForm($id);
        //$editForm = $this->createEditForm($entity);
        $editForm = $this->createForm(new CalendarCategoriesType(), $entity);
        $editForm->bind($request);
        // var_dump($editForm);
        //   exit(1);
        //$editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('calendarcategories'));
        }
        //else{echo "not valide<br>";} 
        return $this->render('ApplicationCalendarBundle:CalendarCategories:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CalendarCategories entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        /*   $form->handleRequest($request); */
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCalendarBundle:CalendarCategories')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CalendarCategories entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('calendarcategories'));
    }

    //==============================================
    //          REQUETES AJAX
    // 
    //==============================================
    public function deleteajaxAction() {
        $request = $this->get('request');
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $id = $request->request->get('id');
            $user_security = $this->container->get('security.context');
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            if (!$user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
                
            }
            $user_id = $this->getuserid();
            if (!isset($user_id)) {
                $array['mystatus'] = "false";
             /*   throw $this->createNotFoundException('Unable to find Changements entity.');*/
                $response = new Response(json_encode($array));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCalendarBundle:CalendarCategories')->find($id);
            if (!$entity) {
            /*    throw $this->createNotFoundException('Unable to find CalendarCategories entity.');*/
                $array['mystatus'] = "notremoved";
                $response = new Response(json_encode($array));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
            $array['mystatus'] = "removed";
            $em->remove($entity);
            $em->flush();
            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * Creates a form to delete a CalendarCategories entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm1($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('calendarcategories_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        /*   ->add('submit', 'submit', array('label' => 'Delete')) */
                        ->getForm()
        ;
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

}
