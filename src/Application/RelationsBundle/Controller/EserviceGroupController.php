<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\RelationsBundle\Entity\EserviceGroup;
use Application\RelationsBundle\Form\EserviceGroupType;

/**
 * EserviceGroup controller.
 *
 */
class EserviceGroupController extends Controller {

    private function getuserid() {


        $em = $this->getDoctrine()->getManager();
        $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $user_id = $user->getId();
            $group = $user->getIdgroup();
            if (isset($group)) {
                $group_id = $group->getId();
            } else {
                $group_id = 0;
            }
        } else {
            $user_id = 0;
            $group_id = 0;
        }


        // }else {
        return array($user_id, $group_id);
        //   }
    }

    /**
     * Lists all EserviceGroup entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        list($user_id, $group_id) = $this->getuserid();
        
        $entities = $em->getRepository('ApplicationRelationsBundle:EserviceGroup')->findAll();

        
        
         $em = $this->getDoctrine()->getManager();
        
        $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
        if (!$current_user) {
            throw $this->createNotFoundException('Unable to find user entity.');
        }
        //echo "groupe=$group_id";
        //   exit(1);
        // $entities = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->findAll();
        $entities_mongroupe = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->getMyPager(
                array('group' => $group_id));

        
        
        
        return $this->render('ApplicationRelationsBundle:EserviceGroup:index.html.twig', array(
                    'entities' => $entities,
                    'entities_mongroupe' => $entities_mongroupe,
            'idgroup'=>$group_id,
                ));
    }

    /**
     * Creates a new EserviceGroup entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new EserviceGroup();
        $form = $this->createForm(new EserviceGroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('egroup_groupes_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationRelationsBundle:EserviceGroup:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to create a new EserviceGroup entity.
     *
     */
    public function newAction() {
        $entity = new EserviceGroup();
        $form = $this->createForm(new EserviceGroupType(), $entity);

        return $this->render('ApplicationRelationsBundle:EserviceGroup:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    public function showmygroupAction() {


        $em = $this->getDoctrine()->getManager();

        list($user_id, $group_id) = $this->getuserid();

        /*
          echo "group=$group_id";
          exit(1);
         */

        if ($user_id == 0) {
            $mymessage = "Vous n'estes pas connecté";
            return $this->render('ApplicationRelationsBundle:EserviceGroup:deny.html.twig', array(
                        'mymessage' => $mymessage,
                    ));
        }
        if ($group_id == 0) {
            $mymessage = "Vous n'appartenez a aucun groupe";
            return $this->render('ApplicationRelationsBundle:EserviceGroup:deny.html.twig', array(
                        'mymessage' => $mymessage,
                    ));
        }

        return $this->redirect($this->generateUrl('egroup_groupes_show', array('id' => $group_id)));
    }

    /**
     * Finds and displays a EserviceGroup entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationRelationsBundle:EserviceGroup')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EserviceGroup entity.');
        }

        list($user_id, $group_id) = $this->getuserid();

        /*
          echo "group=$group_id";
          exit(1);
         */
        $status_group = false;
        if ($user_id == 0) {
            $mymessage = "Vous n'estes pas connecté";
            return $this->render('ApplicationRelationsBundle:EserviceGroup:deny.html.twig', array(
                        'mymessage' => $mymessage,
                    ));
        }
        // si usergroup == id du groupe en cours
        if ($group_id == $id) {
            $status_group = true;
        }


        $deleteForm = $this->createDeleteForm($id);
        return $this->render('ApplicationRelationsBundle:EserviceGroup:show.html.twig', array(
                    'entity' => $entity,
                    'status_group' => $status_group,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing EserviceGroup entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:EserviceGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EserviceGroup entity.');
        }

        $editForm = $this->createForm(new EserviceGroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:EserviceGroup:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing EserviceGroup entity.
     *
     */
    public function updateAction(Request $request, $id) {
        /* echo "update groupe<br>";
          exit(1);
         */
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:EserviceGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EserviceGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EserviceGroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('egroup_groupes', array('id' => $id)));
        }

        return $this->render('ApplicationRelationsBundle:EserviceGroup:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a EserviceGroup entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationRelationsBundle:EserviceGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EserviceGroup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('egroup_groupes'));
    }

    /**
     * Creates a form to delete a EserviceGroup entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    public function quittergroupeAction() {

        list($user_id, $group_id) = $this->getuserid();
        if ($user_id == 0) {
            $mymessage = "Vous n'estes pas connecté";
            return $this->render('ApplicationRelationsBundle:EserviceGroup:deny.html.twig', array(
                        'mymessage' => $mymessage,
                    ));
        }
        if ($group_id == 0) {
            $mymessage = "Vous n'appartenez a aucun groupe";
            return $this->render('ApplicationRelationsBundle:EserviceGroup:deny.html.twig', array(
                        'mymessage' => $mymessage,
                    ));
        }
        // user->group a null
        $em = $this->getDoctrine()->getManager();
        $entity_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
         if ($entity_user) {
        $em->persist($entity_user);
        $entity_user->setIdgroup();
        $em->flush();
         }
        // Supprimer les demandes de group du user
        $em = $this->getDoctrine()->getManager();
        $entity_demandegroupe = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->findOneBy(
                array('iduser' => $user_id)
        );
        if ($entity_demandegroupe) {

            $em->remove($entity_demandegroupe);
            $em->flush();
        }
        /* $em->persist($entity_demandegroupe);
          $entity_user->setIdgroup();
          $em->flush(); */

        return $this->redirect($this->generateUrl('egroup_groupes'));
    }

}
