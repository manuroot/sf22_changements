<?php

namespace Application\CalendarBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\CalendarBundle\Entity\CalendarRoot;
use Application\CalendarBundle\Form\CalendarRootType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\Entry;
use Symfony\Component\Security\Acl\Domain\Acl;
use Symfony\Component\Security\Acl\Dbal\MutableAclProvider;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

/**
 * CalendarRoot controller.
 *
 */
class CalendarRootController extends Controller {

    public function findAcesForUser($user) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('class_type', 'class_type');
        $rsm->addScalarResult('object_identifier', 'object_identifier');
        $rsm->addScalarResult('mask', 'mask');

        $query = $this->em->createNativeQuery(
                'SELECT c.class_type, oid.object_identifier, e.mask ' .
                'FROM acl_security_identities sid ' .
                'JOIN acl_entries e ON sid.id = e.security_identity_id ' .
                'JOIN acl_object_identities oid ON (e.class_id = oid.class_id AND (e.object_identity_id = oid.id OR e.object_identity_id IS NULL)) ' .
                'JOIN acl_classes c ON oid.class_id = c.id ' .
                'WHERE sid.username AND sid.identifier = :entityName || \'-\' || :username '
                , $rsm);
        $query->setParameter('entityName', get_class($user));
        $query->setParameter('username', $user->getUsername());

        $result = array();
        foreach ($query->getResult() as $i => $row) {
            $result[$i]['objectType'] = $row['class_type'];
            $result[$i]['objectIdentifier'] = $row['object_identifier'];
            $result[$i]['permissions'] = MaskBuilder::analyzeMask($row['mask']);
        }

        return $result;
    }

    /**
     * Lists all CalendarRoot entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
  $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user_id = $this->get('security.context')->getToken()->getUser()->getId();
        }
     $entities = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->myFindAll($user_id);

        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
         /* ==========================
         * MAJ ALL ENTITIES
         * ========================== */
        $aclManager = $this->get('problematic.acl_manager');
          foreach ($entities as $entity){
          //$aclManager->setClassPermission($entity, MaskBuilder::MASK_OWNER, $user);
          $aclManager->addObjectPermission($entity, MaskBuilder::MASK_OWNER, $user);
          }
        
        /* ==========================
         * PRELOAD ACL POUR TWIG
         * ========================== */
        $aclProvider = $this->get('security.acl.provider');
        $oids = array();
        foreach ($entities as $object) {
            $oids[] = ObjectIdentity::fromDomainObject($object);
        }
        // preload acls
        $aclProvider->findAcls($oids); // preload Acls from database
        /*
        foreach ($entities as $bar) {
            if ($securityContext->isGranted('EDIT', $bar)) {
                echo "ok"; // permitted";
            } else {
                echo "deny";       // denied
            }
        }
        */
     return $this->render('ApplicationCalendarBundle:CalendarRoot:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new CalendarRoot entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new CalendarRoot();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
      if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // $securityContext = $this->get('security.context');
            //  $user = $securityContext->getToken()->getUser();

            /*   $aclManager = $this->get('problematic.acl_manager');

              // Adds a permission no matter what other permissions existed before
              $aclManager->addObjectPermission($entity, MaskBuilder::MASK_OWNER, $user);
             */

            // creating the ACL
            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($entity);
            $acl = $aclProvider->createAcl($objectIdentity);

            // retrieving the security identity of the currently logged-in user
            $securityContext = $this->get('security.context');
            $user = $securityContext->getToken()->getUser();
            $securityIdentity = UserSecurityIdentity::fromAccount($user);
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);


            return $this->redirect($this->generateUrl('calendarroot_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCalendarBundle:CalendarRoot:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a CalendarRoot entity.
     *
     * @param CalendarRoot $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CalendarRoot $entity) {
        $form = $this->createForm(new CalendarRootType(), $entity, array(
            'action' => $this->generateUrl('calendarroot_create'),
            'method' => 'POST',
        ));

        // $form->add('submit', 'submit', array('label' => 'Create'))                ;

        return $form;
    }

    /**
     * Displays a form to create a new CalendarRoot entity.
     *
     */
    public function newAction() {
        $entity = new CalendarRoot();
        $form = $this->createCreateForm($entity);

        return $this->render('ApplicationCalendarBundle:CalendarRoot:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CalendarRoot entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarRoot entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCalendarBundle:CalendarRoot:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing CalendarRoot entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->find($id);

        $securityContext = $this->get('security.context');
        // Soit owner du record soit admin
        if (false === $securityContext->isGranted('EDIT', $entity)) {
            //    if (false === $securityContext->isGranted('OWNER', $entity) && false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }//else { echo "ok access";}

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarRoot entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCalendarBundle:CalendarRoot:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CalendarRoot entity.
     *
     * @param CalendarRoot $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CalendarRoot $entity) {
        $form = $this->createForm(new CalendarRootType(), $entity, array(
            'action' => $this->generateUrl('calendarroot_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        //  $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing CalendarRoot entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CalendarRoot entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            /*
             * Normalement acl deja existant lors d'un update
             * 
             */
            // creating the ACL
           /* $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($entity);
            //    $acl = $aclProvider->createAcl($objectIdentity);
            try {
                $acl = $aclProvider->createAcl($objectIdentity);
            } catch (\Exception $e) {
                $acl = $aclProvider->findAcl($objectIdentity);
            }
            // preload acls
//$aclProvider->findAcl($oids); // preload Acls from database
            // retrieving the security identity of the currently logged-in user
            $securityContext = $this->get('security.context');
            $user = $securityContext->getToken()->getUser();
            $securityIdentity = UserSecurityIdentity::fromAccount($user);
            
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);*/
            return $this->redirect($this->generateUrl('calendarroot'));
        } else {
            echo "not valid";
        }

        return $this->render('ApplicationCalendarBundle:CalendarRoot:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CalendarRoot entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCalendarBundle:CalendarRoot')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CalendarRoot entity.');
            }


            $aclManager = $this->get('problematic.acl_manager');
            $aclManager->deleteAclFor($entity);
            /* $aclProvider = $this->get('security.acl.provider');
              $objectIdentity = ObjectIdentity::fromDomainObject($entity);
              $aclProvider->deleteAcl($objectIdentity); */
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('calendarroot'));
    }

    /**
     * Creates a form to delete a CalendarRoot entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('calendarroot_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
