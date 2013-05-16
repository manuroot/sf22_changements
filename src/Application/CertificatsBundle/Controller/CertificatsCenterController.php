<?php

namespace Application\CertificatsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Application\CertificatsBundle\Entity\CertificatsCenter;
use Application\CertificatsBundle\Form\Certificats\CertificatsCenterType;
use Application\CertificatsBundle\Form\Certificats\CertificatsCenterCheckType;
use Application\CertificatsBundle\Form\Certificats\CertificatsCenterFiltresType;

use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;

//use APY\DataGridBundle\Grid\Export\CSVExport;
//use APY\DataGridBundle\Grid\Export\ExcelExport;

/**
 * CertificatsCenter controller.
 *
 */
class CertificatsCenterController extends Controller {

    /**
     * Lists all CertificatsCenter entities.
     *
     */
    public static function myStaticMethod(array $ids) {
        // Do whatever you want with these ids
    }

    public function myMethod(array $ids) {
        // Do whatever you want with these ids
    }

    public function indexAction() {
        /*
          $message = \Swift_Message::newInstance()
          ->setSubject('Hello Email')
          ->setFrom('send@example.com')
          ->setTo('mroot72000@yahoo.fr')
          ->setBody(
          $this->renderView(
          'ApplicationCertificatsBundle:CertificatsCenter:email.txt.twig'
          )
          )
          ;
          $this->get('mailer')->send($message); */
        /* $session = new Session();
          $session->start();
          // définit et récupère des attributs de session
          $session->set('name', 'Drak');
          $session->get('name');

          // définit des messages dits « flash »
          $session->getFlashBag()->add('notice', 'Profile updated'); */
//$this->get('session')->getFlashBag()->set('type', 'message');
        // ajoute des messages flash
        /* $session->getFlashBag()->add('warning', 'Your config file is writable, it should be set read-only');
          $session->getFlashBag()->add('error', 'Failed to update name');
          $session->getFlashBag()->add('error', 'Another error'); */
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'certificatscenter');
          $searchForm = $this->createForm(new CertificatsCenterFiltresType());
//$foo = $session->get('foo');
        //  $session = new Session();
//$session->start();
        //$searchForm = $this->createSearchForm();
           //$form = $this->get('form.factory')->create(new PostFilterType());
           // A revoir pour combiner les 2 queries
          
          if ($this->get('request')->query->has('submit-filter')) {
             // echo "submit filters";exit(1);
            // bind values from the request
            $searchForm->bind($this->get('request'));
            $filterBuilder = $this->get('doctrine.orm.entity_manager')
                    ->getRepository('ApplicationCertificatsBundle:CertificatsCenter')
                   // ->createQueryBuilder('e');
             ->createQueryBuilder('a')
                        ->select('a,b,c')
                        ->leftJoin('a.project', 'b')
                        ->leftJoin('a.typeCert', 'c')
                        ->orderBy('a.id', 'DESC');
                 $query = $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($searchForm, $filterBuilder);
        } else {
            $em = $this->getDoctrine()->getManager();
            $query = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->myFindaAll();
        }
      //  $em = $this->getDoctrine()->getManager();
        //$query = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->myFindaAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        $pagination->setTemplate('ApplicationCertificatsBundle:pagination:sliding.html.twig');
        //$pagination->setTemplate('ApplicationMyNotesBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:index.html.twig', array(
                    'pagination' => $pagination,
                    'search_form' => $searchForm->createView(),
                ));
//return compact('pagination');
    }

    public function checkcertAction() {

        $form = $this->createForm(new CertificatsCenterCheckType());
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:checkcert.html.twig', array(
                    'form' => $form->createView(),
                ));
    }

    //public function editAction(Request $request, $id) {
    public function validatecheckcertAction(Request $request) {
        
        $vForm = $this->createForm(new CertificatsCenterCheckType());
      //  print_r($request->getMethod());
      // exit(1);
        if ($request->getMethod() == 'POST') {
             
            
            $postData = $request->request->get('checkcert');
            //Array ( [opecert] => 0 [typecert] => 0 [contenu] => ghjgjhgjhgjhgyuiyuiyi
          //  print_r($postData);
          //  exit(1);
            // $editForm->bind($request);
           // if ($vForm->isValid()) {
                $vForm->bind($postData);
                return $this->render('ApplicationCertificatsBundle:CertificatsCenter:checkcert.html.twig', array(
                            'datas' => $postData,
                     'form' => $vForm->createView(),
                        ));
           // }
        }

        //   var_dump($request->request->all());
        /* var_dump($postData);
          exit(1); */
//   unset($postData['id']);
        //      var_dump($postData);



        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:validatecert.html.twig', array(
                ));


        /*  return $this->render('ApplicationCertificatsBundle:CertificatsCenter:checkcert.html.twig', array(
          'form' => $form->createView(),
          )); */
    }

    /**
     * Finds and displays a CertificatsCenter entity.
     *
     */
    public function showAction($id) {
        //   public function showAction(CertificatsCenter $certificat) {
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id);

        $applis = $entity->getIdapplis();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsCenter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:show.html.twig', array(
                    'entity' => $entity,
                    'btnretour' => $myretour,
                    'delete_form' => $deleteForm->createView(),
                    'applis' => $applis,
                ));
    }

    /**
     * Displays a form to create a new CertificatsCenter entity.
     *
     */
    public function newAction() {
        $entity = new CertificatsCenter();
        $form = $this->createForm(new CertificatsCenterType(), $entity);
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');
        // print_r($myretour);
        //  exit(1);
        if (!isset($myretour)) {
            $myretour = 'certificatscenter';
        }
        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'btnretour' => $myretour,
                ));
    }

    /**
     * Creates a new CertificatsCenter entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new CertificatsCenter();
        $form = $this->createForm(new CertificatsCenterType(), $entity);
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // creating the ACL
            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($entity);
            $acl = $aclProvider->createAcl($objectIdentity);

            // retrieving the security identity of the currently logged-in user
            $securityContext = $this->get('security.context');
            $user = $securityContext->getToken()->getUser();
            $securityIdentity = UserSecurityIdentity::fromAccount($user);
            /*  $builder = new MaskBuilder();
              $builder
              ->add('view')
              ->add('edit')
              ->add('delete')
              ->add('undelete')
              ;
              $mask = $builder->get(); // int(29) */
            // grant owner access
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);




            $session = $this->getRequest()->getSession();
            // ajoute des messages flash
            $id = $entity->getId();
            $session->getFlashBag()->add('warning', "Enregistrement $id ajout successfull");

            return $this->redirect($this->generateUrl('certificatscenter_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'btnretour' => $myretour,
                ));
    }

    /**
     * Displays a form to edit an existing CertificatsCenter entity.
     *
     */
    public function editAction($id) {

        $em = $this->getDoctrine()->getManager();


// stocke un attribut pour une réutilisation lors d'une future requête utilisateur
//$session->set('buttonretour', 'bar');
// dans un autre contrôleur pour une autre requête
        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');
        if (!isset($myretour)) {
            $myretour = 'certificatscenter';
        }
        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id);

        // check rights
        $securityContext = $this->get('security.context');


        // Soit owner du record soit admin
        if (false === $securityContext->isGranted('OWNER', $entity)
                && false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        //      if ($securityIdentity == $acl)
        /*   if ($this->securityContext->isGranted('OWNER', $this->objectIdentity) === false) {

          }
          if ($acl->isGranted( array(MaskBuilder::MASK_OWNER), array( $securityIdentity) ))

          {
          throw new AccessDeniedException();
          }
         */

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsCenter entity.');
        }

        $editForm = $this->createForm(new CertificatsCenterType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'btnretour' => $myretour,
                        //   'acl'=>$acl,
                        //  'idty'=>$securityIdentity,
                ));
    }

    /**
     * Edits an existing CertificatsCenter entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsCenter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CertificatsCenterType(), $entity);

        $postData = $request->request->get('moncert');
        //var_dump($request->request->all());
        //   unset($postData['id']);
        //      var_dump($postData);

        $editForm->bind($postData);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $session = $this->getRequest()->getSession();
            // ajoute des messages flash
            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");

            //  $session = $this->getRequest()->getSession();
            $route_back = $session->get('buttonretour');
            if (isset($route_back))
                return $this->redirect($this->generateUrl($route_back, array('id' => $id)));
            else
                return $this->redirect($this->generateUrl('certificatscenter'));
        }

        return $this->render('ApplicationCertificatsBundle:CertificatsCenter:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a CertificatsCenter entity.
     *
     */
    //==============================================
    // SUPPRIMER ACTEUR
    //==============================================

    /**
      // @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction($id) {


        $session = $this->getRequest()->getSession();
        $myretour = $session->get('buttonretour');


        $em = $this->getDoctrine()->getManager();
        $em = $this->container->get('doctrine')->getEntityManager();
        $cert = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id);
        //$cert = $em->find('ApplicationCertificatsBundle:CertificatsCenter', $id);
        if (!$cert) {
            throw new NotFoundHttpException("Note non trouvée");
        }

        $aclProvider = $this->get('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($cert);
        $aclProvider->deleteAcl($objectIdentity);

        /*
          $aclProvider = $this->get('security.acl.provider');
         * $aclProvier->deleteAcl($objectIdentity) 
          $objectIdentity = ObjectIdentity::fromDomainObject($cert);
          $acl = $aclProvider->createAcl($objectIdentity);
          $aclProvider->deleteAcl($acl);
         */
        $em->remove($cert);
        $em->flush();

        return $this->redirect($this->generateUrl('certificatscenter_viewapy'));
        // return new RedirectResponse($this->container->get('router')->generate('notesfanta'));
    }

    //==============================================
    // SUPPRIMER CERTS AUTOGENERATION
    // not working
    //==============================================

    public function deleteActionaa(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CertificatsCenter entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('certificatscenter'));
    }

    protected function createSearchForm() {

        return $this->createFormBuilder()
                        ->add('Serveur', 'text', array('label' => 'Serveur'))
                        ->add('amount', 'choice', array(
                            'label' => 'Montant en euros',
                            'choices' => array(
                                1 => 1,
                                2 => 2,
                                10 => 10, 20 => 20, 50 => 50, 100 => 100, 200 => 200),
                            'preferred_choices' => array(10),
                        ))
                        //->add('currency', null, array('data' => 'EUR', 'label' => 'Devise'))
                        ->add('item_name', 'hidden', array(
                            'data' => 'Participation Au Blog MROOT',
                        ))
                        ->getForm()
        ;
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm();
    }

    //==============================================
    // VIEW ALL ACTEURS
    //==============================================
    public function viewapyAction($page = 1) {

        $session = $this->getRequest()->getSession();
        // ajoute des messages flash
        $session->set('buttonretour', 'certificatscenter_viewapy');
        $source = new Entity('ApplicationCertificatsBundle:CertificatsCenter');


        $source->manipulateRow(
                function ($row) {
                    // Don't show the row if the price is greater than $maxPrice
                    $past = date('Y-m-d', strtotime('+30days'));
                    $currenta = ($row->getField('endTime')->format('Y-m-d'));
                    $current = date('Y-m-d', strtotime($currenta));
                    //$current = new \DateTime($row->getField('endTime')->format('Y-m-d'));
                    //$current = date('Y-m-d', strtotime($row->getField('endTime')));
                    if ($current < $past) {
                        $row->setColor('#fcf8e3');
                    }

                    return $row;
                }
        );
        /*         *   {% if date(value) < date('-30days') %}
         */

        /*    $source->manipulateRow(
          function ($row)
          {
          //   if (date($row->getField('endTime')) < date('-30days')) {
          if (date($row->getField('endTime')->format('Y-m-d')) < date('-30days')->) {
          $row->setColor('#00ff00');
          }


          return $row;
          }
          ); */

//$grid->setSource($source); 

        $grid = $this->container->get('grid');
        // Attach the source to the grid
        $grid->setSource($source);

        $grid->setId('certificatsgrid');
        //chiant si error
        $grid->setPersistence(false);
        $grid->setDefaultOrder('id', 'desc');
        // Set the selector of the number of items per page
        $grid->setLimits(array(10));

        // Set the default page
        $grid->setPage($page);
        $grid->addMassAction(new DeleteMassAction());
        $grid->setActionsColumnSize(70);
        $myRowActiona = new RowAction('Edit', 'certificatscenter_edit', false, '_self', array('class' => "btn btn-mini btn-warning"));
        $grid->addRowAction($myRowActiona);
        $myRowAction = new RowAction('Delete', 'certificatscenter_delete', true, '_self', array('class' => "btn btn-mini btn-danger"));
        //$myRowAction = new RowAction('Delete', 'certificatscenter_delete', true, '_self',array('class' => 'deleteme'));
        $grid->addRowAction($myRowAction);
        // Return the response of the grid to the template
        return $grid->getGridResponse('ApplicationCertificatsBundle:CertificatsCenter:indexapy.html.twig');
    }

    public function listByProjetAction() {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getEntityManager();
            $id = '';
            $applis = array();
            $cert_app = array();

            $id = $request->request->get('id_projet');
            $projet = $em->getRepository('ApplicationCertificatsBundle:Projet')->find($id);

            $id_cert = $request->request->get('id_cert');
            if (isset($id_cert) && $id_cert != "create") {
                //    var_dump($id_cert);
                $cert = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->find($id_cert);
                foreach ($cert->getIdapplis() as $appli) {
                    array_push($cert_app, $appli->getId());
                }
                $applis['cert'] = $cert_app;
            }
            foreach ($projet->getIdapplis() as $appli) {
                //$applis[] = array($appli);
                $applis['applis'][$appli->getId()] = $appli->getNomapplis();
                //      $applis[] = array($appli->getId(), $appli->getNomapplis());
            }

            //    $appli=array(3,4);
            $response = new Response(json_encode($applis));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        // return new Response();
    }

}
