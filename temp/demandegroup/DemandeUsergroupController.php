<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\RelationsBundle\Entity\DemandeUsergroup;
use Application\RelationsBundle\Form\DemandeUsergroupType;
use Application\RelationsBundle\Form\DemandeUsergroupAdminType;
use Application\EservicesBundle\Entity\EserviceGroup;
use Application\EservicesBundle\Form\EserviceGroupType;

/**
 * DemandeUsergroup controller.
 *
 */
class DemandeUsergroupController extends Controller {

    private function getuserid() {


        $em = $this->getDoctrine()->getManager();
        $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $user_id = $user->getId();
            $group = $user->getIdgroup();
            if (isset($group)){
                  $group_id=$group->getId();
            }
            else {
                $group_id=0;
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
     * Lists all DemandeUsergroup entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->findAll();

        return $this->render('ApplicationRelationsBundle:DemandeUsergroup:index.html.twig', array(
                    'entities' => $entities,
                ));
    }

    /**
     * Creates a new DemandeUsergroup entity.
     *
     */
    public function createAction(Request $request) {
        echo "creation here";exit(1);
        $entity = new DemandeUsergroup();
        $form = $this->createForm(new DemandeUsergroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
       /*
         $entity_group = $em->getRepository('ApplicationEservicesBundle:EserviceGroup')->find($id);
        if (!$entity_group) {
            throw $this->createNotFoundException('Unable to find EserviceGroup entity.');
        }*/
        list($user_id, $group_id) = $this->getuserid();
         $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
       if (!$current_user) {
            throw $this->createNotFoundException('Unable to find user entity.');
        }
       
          /* echo "test id=$user_id";
       exit(1);*/
       $entity_user = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->findByIduser($user_id);

        if ($entity_user) {
            throw $this->createNotFoundException('Demande existe deja.');
        }
       //$entity->setIdgroup($entity_group);
     //   $entity->setIduser($current_user);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('groupedemande_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationRelationsBundle:DemandeUsergroup:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }
 public function createAdminAction(Request $request) {
       $securityContext = $this->container->get('security.context');
        $entity = new DemandeUsergroup();
        $form = $this->createForm(new DemandeUsergroupAdminType($securityContext), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('groupedemande_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationRelationsBundle:DemandeUsergroup:newadmin.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }
    /**
     * Displays a form to create a new DemandeUsergroup entity.
     *
     */
    public function newAction() {
        $securityContext = $this->container->get('security.context');
          list($user_id, $group_id) = $this->getuserid();
         $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
       if (!$current_user) {
            throw $this->createNotFoundException('Unable to find user entity.');
        }
        
          /* echo "test id=$user_id ";
       exit(1);*/
          $entity_user = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->findByIduser($user_id);

          
          
          
        $entity = new DemandeUsergroup();
        $form = $this->createForm(new DemandeUsergroupAdminType($securityContext), $entity);

        return $this->render('ApplicationRelationsBundle:DemandeUsergroup:newadmin.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    public function newDemandegroupAction($id) {
        

      //  echo "test id=$id";
      //  exit(1);
        $em = $this->getDoctrine()->getManager();
         $entity_group = $em->getRepository('ApplicationEservicesBundle:EserviceGroup')->find($id);
        if (!$entity_group) {
            throw $this->createNotFoundException('Unable to find EserviceGroup entity.');
        }
        list($user_id, $group_id) = $this->getuserid();
         $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
       if (!$current_user) {
            throw $this->createNotFoundException('Unable to find user entity.');
        }
        
          /* echo "test id=$user_id ";
       exit(1);*/
          $entity_user = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->findByIduser($user_id);

        if ($entity_user) {
            $message="Cette demande existe deja";
            return $this->render('ApplicationRelationsBundle:DemandeUsergroup:deny.html.twig', array(
                'mymessage'=>$message));
        }
       
        //Creation entity EproduitComments
        $entity = new DemandeUsergroup();
        $entity->setIdgroup($entity_group);
        $entity->setIduser($current_user);
        $form = $this->createForm(new DemandeUsergroupType(), $entity);
   // $entity = new DemandeUsergroup();
   //     $form = $this->createForm(new DemandeUsergroupType(), $entity);

        return $this->render('ApplicationRelationsBundle:DemandeUsergroup:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Finds and displays a DemandeUsergroup entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DemandeUsergroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:DemandeUsergroup:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing DemandeUsergroup entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DemandeUsergroup entity.');
        }

        $editForm = $this->createForm(new DemandeUsergroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationRelationsBundle:DemandeUsergroup:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing DemandeUsergroup entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DemandeUsergroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DemandeUsergroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('groupedemande_edit', array('id' => $id)));
        }

        return $this->render('ApplicationRelationsBundle:DemandeUsergroup:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a DemandeUsergroup entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DemandeUsergroup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('groupedemande'));
    }

    /**
     * Creates a form to delete a DemandeUsergroup entity by id.
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

    protected function getChangement($changement_id) {
        $em = $this->getDoctrine()
                ->getEntityManager();

        $changements = $em->getRepository('ApplicationChangementsBundle:Changements')->find($changement_id);

        if (!$changements) {
            throw $this->createNotFoundException('Unable to find Changement.');
        }

        return $changements;
    }

}
