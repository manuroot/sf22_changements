<?php

namespace Application\ChangementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\ChangementsBundle\Entity\Changements;
use Application\ChangementsBundle\Entity\ChangementsComments;
use Application\ChangementsBundle\Form\ChangementsCommentsType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Eproduit controller.
 *
 */
class ChangementsCommentsController extends Controller {

    //$defaut_paginator=array('pagename'=>'page1','sort'=>'sort1','sortfield'=>'sort1');
    private function createpaginator($query, $num_perpage = 5, $defaut_paginator = null) {

        $paginator = $this->get('knp_paginator');

        $sortDirectionParameterName = 'sortDirectionParameterName';
        $sortFieldParameterName = 'sortFieldParameterName';
        $pagename = 'page'; // Set custom page variable name
        // Ajouter des controles

        if (is_array($defaut_paginator)) {
            $pagename = $defaut_paginator['pagename'];
            $sortDirectionParameterName = $defaut_paginator['sortdir'];
            $sortFieldParameterName = $defaut_paginator['sortfield'];
        }

        $page = $this->get('request')->query->get($pagename, 1); // Get custom page variable

        $pagination = $paginator->paginate(
                $query, $page, $num_perpage, array('pageParameterName' => $pagename,
            "sortDirectionParameterName" => $sortDirectionParameterName,
            'sortFieldParameterName' => $sortFieldParameterName)
        );

        $pagination->setTemplate('ApplicationEpostBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $pagination;
    }

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
        return array($user_id, $group_id);
    }

    public function newAction($changement_id) {

        $em = $this->getDoctrine()->getManager();
        list($user_id, $group_id) = $this->getuserid();
        $validation = 0;
        $comment = null;
        if ($user_id != 0) {
            $validation = 1;
            $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
            $changement = $this->getChangement($changement_id);
            $comment = new ChangementsComments();
            $comment->setChangement($changement);
            $comment->setUser($current_user);
            $form = $this->createForm(new ChangementsCommentsType(), $comment);
            $formview = $form->createView();
        }

        return $this->render('ApplicationChangementsBundle:ChangementsComments:form.html.twig', array(
                    'comment' => $comment,
                    'form' => $formview,
                    'validation' => $validation,
                ));
    }

    public function showAction($id) {

        $em = $this->getDoctrine()->getManager();
        list($user_id, $group_id) = $this->getuserid();

        $validation = 1;
        // recup du changement:
        $em = $this->getDoctrine()->getEntityManager();
        $changement = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);
        if (!$changement) {
            throw $this->createNotFoundException('Unable to find Changement.');
        }


        $comments = $em->getRepository('ApplicationChangementsBundle:ChangementsComments')
                ->getCommentsForChangement($id);




        /*  $comments = $em->getRepository('ApplicationEpostBundle:EpostComments')
          ->getCommentsForPost($entity->getId());
         */

        $paginationa = $this->createpaginator($comments, 5);


        if ($user_id != 0) {
            $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);

            //Creation entity EproduitComments
            $comment_entity = new ChangementsComments();
            $comment_entity->setChangement($changement);
            $comment_entity->setUser($current_user);
            $form = $this->createForm(new ChangementsCommentsType(), $comment_entity);

            return $this->render('ApplicationChangementsBundle:ChangementsComments:show.html.twig', array(
                        'entity' => $changement,
                        'paginationa' => $paginationa,
                         'form' => $form->createView(),
                        'validation' => $validation,
                    ));
        } else {
            // throw $this->createNotFoundException('User not connected.');
            $validation = 0;
            return $this->render('ApplicationChangementsBundle:ChangementsComments:show.html.twig', array(
                        'entity' => $changement,
                    'paginationa' => $paginationa,
                        'validation' => $validation,
                    ));
        }
    }

    public function createAction($changement_id) {



        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            //  if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
            $user_id = $user->getId();
        } else {
            throw $this->createNotFoundException('User not connected.');
        }
        $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);

        /*  $comment = new ChangementsComments();
          $comment->setChangement($changement);
          $comment->setUser($current_user);
          $form   = $this->createForm(new ChangementsCommentsType(), $comment);
         */

        $changement = $this->getChangement($changement_id);
        $comment = new ChangementsComments();
        $comment->setChangement($changement);
        //logged user:
        $comment->setUser($current_user);
        $request = $this->getRequest();
        $form = $this->createForm(new ChangementsCommentsType(), $comment);
        $form->bindRequest($request);



        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirect($this->generateUrl('changements_comment_show', array(
                                'id' => $comment->getChangement()->getId()))
            );
        }
        // $produit = $this->getComments($produit_id);
    }

    protected function getChangement($changement_id) {
        $em = $this->getDoctrine()->getEntityManager();
        $changements = $em->getRepository('ApplicationChangementsBundle:Changements')->find($changement_id);
        if (!$changements) {
            throw $this->createNotFoundException('Unable to find Changement.');
        }

        return $changements;
    }

}
