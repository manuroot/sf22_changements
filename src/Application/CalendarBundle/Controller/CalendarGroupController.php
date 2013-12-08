<?php

namespace Application\CalendarBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use JMS\SecurityExtraBundle\Annotation\Secure;
//use APY\DataGridBundle\Grid\Export\PHPExcelPDFExport;
//use APY\DataGridBundle\Grid\Export\ExcelExport;
use Symfony\Component\HttpFoundation\JsonResponse;


use Application\CalendarBundle\Entity\CalendarGroup;
use Application\CalendarBundle\Form\CalendarGroupType;

/**
 * CalendarGroup controller.
 *
 */
class CalendarGroupController extends Controller
{

    /**
     * Lists all CalendarGroup entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
       $entities = $em->getRepository('ApplicationCalendarBundle:CalendarGroup')->findAll();
        return $this->render('ApplicationCalendarBundle:CalendarGroup:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    
    
     /**
     *  @Secure(roles="ROLE_USER")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $id
     * @return type
     * @throws type
     * 
     */
    public function indexmesgroupesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user_id = $this->get('security.context')->getToken()->getUser()->getId();
      
           $entities = $em->getRepository('ApplicationCalendarBundle:CalendarGroup')->myFindAll($user_id);
$entities_ingroup = $em->getRepository('ApplicationCalendarBundle:CalendarGroup')->myFindInGroupAll($user_id);
           
        return $this->render('ApplicationCalendarBundle:CalendarGroup:index_mesgroupes.html.twig', array(
            'entities' => $entities,
            'entities_ingroup' => $entities_ingroup,
        ));
    }
    }
    
    
    /**
     * Creates a new CalendarGroup entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CalendarGroup();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('calendargroup_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCalendarBundle:CalendarGroup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a CalendarGroup entity.
    *
    * @param CalendarGroup $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CalendarGroup $entity)
    {
        $form = $this->createForm(new CalendarGroupType(), $entity, array(
            'action' => $this->generateUrl('calendargroup_create'),
            'method' => 'POST',
        ));

      //  $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CalendarGroup entity.
     *
     */
    public function newAction()
    {
        $entity = new CalendarGroup();
        //$form = $this->createForm(new CalendarGroupType(), $entity,
        $form   = $this->createCreateForm($entity);

        return $this->render('ApplicationCalendarBundle:CalendarGroup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CalendarGroup entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCalendarBundle:CalendarGroup:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CalendarGroup entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarGroup entity.');
        }

         //  $editForm = $this->createForm(new CalendarGroupType(), $entity);
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCalendarBundle:CalendarGroup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CalendarGroup entity.
    *
    * @param CalendarGroup $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CalendarGroup $entity)
    {
        $form = $this->createForm(new CalendarGroupType(), $entity, array(
            'action' => $this->generateUrl('calendargroup_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

     //   $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CalendarGroup entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
         $editForm = $this->createForm(new CalendarGroupType(), $entity);
        //$editForm = $this->createEditForm($entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('calendargroup'));
        }

        return $this->render('ApplicationCalendarBundle:CalendarGroup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CalendarGroup entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCalendarBundle:CalendarGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CalendarGroup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('calendargroup'));
    }

    /**
     * Creates a form to delete a CalendarGroup entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('calendargroup_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
