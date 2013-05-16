<?php

namespace Application\RelationsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\RelationsBundle\Entity\DemandeUsergroup;
use Application\RelationsBundle\Form\DemandeUsergroupType;
use Application\RelationsBundle\Form\DemandeUsergroupAdminType;
use Application\RelationsBundle\Entity\EserviceGroup;
use Application\EservicesBundle\Form\EserviceGroupType;

/**
 * DemandeUsergroup controller.
 *
 */
class DemandeUsergroupController extends Controller {

    private function getuserid() {


        // $em = $this->getDoctrine()->getManager();
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
     * Lists all DemandeUsergroup entities.
     *
     */
    public function indexMonGroupeAction() {
        $em = $this->getDoctrine()->getManager();
        list($user_id, $group_id) = $this->getuserid();
        $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
        if (!$current_user) {
            throw $this->createNotFoundException('Unable to find user entity.');
        }
        //echo "groupe=$group_id";
        //   exit(1);
        // $entities = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->findAll();
        $entities = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->getMyPager(
                array('group' => $group_id));

        return $this->render('ApplicationRelationsBundle:DemandeUsergroup:indexmongroupe.html.twig', array(
                    'entities' => $entities,
                ));
    }

    /* ==================================================================
     * 
     *  Accepter une demande de groupe
      ================================================================== */

    public function acceptAction(Request $request, $id) {

        $session = $this->getRequest()->getSession();
        if (!$id) {
            throw $this->createNotFoundException('Missing paramter id = ' . $id);
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DemandeUsergroup entity.');
        }
        $acceptform = $this->createAcceptForm($id);

        $demandeur_id = $entity->getIduser()->getId();
        $demandeur_group_id = $entity->getIdgroup()->getId();



        list($user_parrain_id, $group_parrain_id) = $this->getuserid();
        $user_security = $this->container->get('security.context');

        if ($user_parrain_id == 0) {
            $message = "Vous n\etes pas connecté !!";
            return $this->render('ApplicationRelationsBundle:DemandeUsergroup:deny.html.twig', array(
                        'mymessage' => $message));
        }

        if ($group_parrain_id == 0) {
            $message = "Vous n\appartenez a aucun groupe !!";
            return $this->render('ApplicationRelationsBundle:DemandeUsergroup:deny.html.twig', array(
                        'mymessage' => $message));
        }
        if (!$user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $message = "Vous n\etes pas connecté !!";
            return $this->render('ApplicationRelationsBundle:DemandeUsergroup:deny.html.twig', array(
                        'mymessage' => $message));
        }
        if ($demandeur_id == $user_parrain_id) {
            $message = "Vous etes le demandeur !!";
            return $this->render('ApplicationRelationsBundle:DemandeUsergroup:deny.html.twig', array(
                        'mymessage' => $message));
        }
        if ($demandeur_group_id != $group_parrain_id) {
            $message = "La demande ne correspond pas a votre groupe";
            return $this->render('ApplicationRelationsBundle:DemandeUsergroup:deny.html.twig', array(
                        'mymessage' => $message));
        }

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $session->getFlashBag()->add('warning', "ADMINISTRATEUR MODE");
        }
        if ($request->getMethod() == 'POST') {

            $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_parrain_id);
            if (!$current_user) {
                throw $this->createNotFoundException('Unable to find user entity.');
            }
            $demandeur_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($demandeur_id);
            if (!$demandeur_user) {
                throw $this->createNotFoundException('Unable to find user entity.');
            }

            $entity->setIsaccepted(true);
            $entity->setIduserParrain($current_user);
            $em->persist($entity);
            $em->flush();

            //$idgroup = $postData['idgroup'];
            $entity_group = $em->getRepository('ApplicationRelationsBundle:EserviceGroup')->find($demandeur_group_id);
           
            
            $demandeur_user->setIdgroup($entity_group);
            $em->persist($demandeur_user);
            $em->flush();

        }
        return $this->render('ApplicationRelationsBundle:DemandeUsergroup:accept.html.twig', array(
                    'entity' => $entity, 'form' => $acceptform->createView()));
    }

    /**
     * Creates a new DemandeUsergroup entity.
     *
     */
    public function createAction(Request $request) {
        // echo "creation here";
        //  exit(1);
        $entity = new DemandeUsergroup();
        $form = $this->createForm(new DemandeUsergroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $postData = $request->request->get('application_relationsbundle_demandeusergrouptype');
            $iduser = $postData['iduser'];
            $em = $this->getDoctrine()->getManager();

            list($user_id, $group_id) = $this->getuserid();
            $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
            if (!$current_user) {
                throw $this->createNotFoundException('Unable to find user entity.');
            }


            //  print_r($postData);
            //    exit(1);
            // Valider demande
            // ==> user group a idgroup
            $idgroup = $postData['idgroup'];

            /* =====================================================
             * 
             *  Creation de la demande user dans table demande_usergroup
             * 
              ====================================================== */
            /* echo "test id=$user_id";
              exit(1); */
            $entity_user = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->findByIduser($user_id);

            if ($entity_user) {
                throw $this->createNotFoundException('Demande existe deja.');
            }
            //$entity->setIdgroup($entity_group);
            //   $entity->setIduser($current_user);
            $em->persist($entity);
            $em->flush();

            // Non !! le set du group dans fosuser kan parrain accepte la demande
            /* $entity_group = $em->getRepository('ApplicationRelationsBundle:EserviceGroup')->find($idgroup);
              $em->persist($current_user);
              $current_user->setIdgroup($entity_group);
              $em->flush(); */


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

        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->container->get('security.context');
        list($user_id, $group_id) = $this->getuserid();
        $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
        if (!$current_user) {
            throw $this->createNotFoundException('Unable to find user entity.');
        }

        /* echo "test id=$user_id ";
          exit(1); */
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
        $entity_group = $em->getRepository('ApplicationRelationsBundle:EserviceGroup')->find($id);
        if (!$entity_group) {
            throw $this->createNotFoundException('Unable to find EserviceGroup entity.');
        }
        list($user_id, $group_id) = $this->getuserid();
        $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
        if (!$current_user) {
            throw $this->createNotFoundException('Unable to find user entity.');
        }

        /* echo "test id=$user_id ";
          exit(1); */
        $entity_user = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->findByIduser($user_id);

        if ($entity_user) {
            $message = "Vous avez deja effectuer une demande de groupe";
            return $this->render('ApplicationRelationsBundle:DemandeUsergroup:deny.html.twig', array(
                        'mymessage' => $message));
        }
        //Creation entity EproduitComments
        $entity = new DemandeUsergroup();
        $entity->setIdgroup($entity_group);
        $entity->setIduser($current_user);
        $form = $this->createForm(new DemandeUsergroupType(), $entity);
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
    public function editAction(Request $request, $id) {

        $session = $this->getRequest()->getSession();
        if (!$id) {
            throw $this->createNotFoundException('Missing paramter id = ' . $id);
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationRelationsBundle:DemandeUsergroup')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DemandeUsergroup entity.');
        }
        $proprietaire = $entity->getIduser()->getId();
        list($user_id, $group_id) = $this->getuserid();
        $user_security = $this->container->get('security.context');

        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            if (!$this->get('security.context')->isGranted('ROLE_ADMIN') && $user_id != $proprietaire) {
                $message = "Vous n'etes pas le proprietaire de cette demande";
                return $this->render('ApplicationRelationsBundle:DemandeUsergroup:deny.html.twig', array(
                            'mymessage' => $message));
            }
        }
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $session->getFlashBag()->add('warning', "ADMINISTRATEUR MODE");
        }
        $editForm = $this->createForm(new DemandeUsergroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        $request = $this->getRequest();
        //  print_r($request->getMethod());
        //    exit(1);
        if ($request->getMethod() == 'POST') {
            $editForm->bind($request);
            if ($editForm->isValid()) {

                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('groupedemande'));
            }
        }
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
    /* public function updateAction(Request $request, $id) {

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

      return $this->redirect($this->generateUrl('groupedemande'));
      }

      return $this->render('ApplicationRelationsBundle:DemandeUsergroup:edit.html.twig', array(
      'entity' => $entity,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
      ));
      } */

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

            $proprietaire = $entity->getIduser()->getId();
            $entity_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($proprietaire);
            $empty_entity_group = new EserviceGroup();

            $em->persist($entity_user);
            $entity_user->setIdgroup();
            $em->flush();
            $em->remove($entity);
            $em->flush();
            //supprimer le groupe dans le user
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

    private function createAcceptForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
