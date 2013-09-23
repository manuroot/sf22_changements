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


use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Export\ExcelExport;
use Ob\HighchartsBundle\Highcharts\Highchart;

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

        $pagination->setTemplate('ApplicationChangementsBundle:pagination:twitter_bootstrap_pagination.html.twig');
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

     // VIEW APY
    //==============================================
    public function indexMesActivitesApyAction($page = 1) {

        $session = $this->getRequest()->getSession();
        // ajoute des messages flash
        $session->set('buttonretour', 'changements_apy');
        $source = new Entity('ApplicationChangementsBundle:ChangementsComments');
        $grid = $this->container->get('grid');
        // Attach the source to the grid
        $grid->setSource($source);

        $grid->setId('changementsgrid');
        //$grid->addExport(new CSVExport('CSV Export', 'Operations', array('delimiter' => ';'), 'Windows-1252'));
        //$grid->addExport(new ExcelExport('Excel Export', 'Operations', array('delimiter' => ';'), 'Windows-1252'));

        $grid->setPersistence(false);
        $grid->setDefaultOrder('id', 'desc');
        // Set the selector of the number of items per page
        $grid->setLimits(array(50));


        // Set the default page
        $grid->setPage($page);
            // Return the response of the grid to the template
        return $grid->getGridResponse('ApplicationChangementsBundle:ChangementsComments:indexmesactivites.html.twig');
    }
    
    
    public function showAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        list($user_id, $group_id) = $this->getuserid();

        $session = $request->getSession();
        $btn_retour = $session->get('buttonretour');
        if ($btn_retour != 'changements_fanta' && $btn_retour != 'changements_myfanta')
             $session->set('buttonretour','changements_fanta');

        $validation = 1;
        // recup du changement:
        $changement = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);
        //$criteria=array('comments'=>0,'byid'=>$id);
        if (!$changement) {
            throw $this->createNotFoundException('Unable to find Changement.');
        }


        $comments = $em->getRepository('ApplicationChangementsBundle:ChangementsComments')
                ->getCommentsForChangement($id);
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

    public function createAction(Request $request, $changement_id) {



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
        // $request = $this->getRequest();
        $form = $this->createForm(new ChangementsCommentsType(), $comment);
        $form->bind($request);



        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirect($this->generateUrl('changements_comment_show', array(
                                'id' => $comment->getChangement()->getId()))
            );
        }
        // $produit = $this->getComments($produit_id);
    }
 public function editAction(Request $request) {

       $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_security = $this->container->get('security.context');
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user_id = $user->getId();
        } else {
            throw $this->createNotFoundException('User not connected.');
        }
        $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
   
       
    }
    protected function getChangement($changement_id) {
        $em = $this->getDoctrine()->getManager();
        $changements = $em->getRepository('ApplicationChangementsBundle:Changements')->find($changement_id);
        if (!$changements) {
            throw $this->createNotFoundException('Unable to find Changement.');
        }

        return $changements;
    }

}
