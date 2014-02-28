<?php

namespace Application\MyNotesBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\MyNotesBundle\Entity\Notes;
use Application\MyNotesBundle\Form\NotesType;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
/*
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;*/
use Symfony\Component\HttpFoundation\Response;

/**
* Notes controller.
*
*/
class NotesController extends Controller {

    /**
* Lists all Notes entities.
*
*/
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ApplicationMyNotesBundle:Notes')->myFindaAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1)/* page number */, 5/* limit per page */
        );
        $pagination->setTemplate('ApplicationMyNotesBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationMyNotesBundle:Notes:index.html.twig', array(
                    'pagination' => $pagination,
                ));
    }

    public function indexisotopeAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ApplicationMyNotesBundle:Notes')->myFindaAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        $pagination->setTemplate('ApplicationMyNotesBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationMyNotesBundle:Notes:indexisotope.html.twig', array(
                    'pagination' => $pagination,
                ));
    }

     public function indextodoAction()
    {
        $em = $this->getDoctrine()->getManager();
    $entities = $em->getRepository('ApplicationMyNotesBundle:Notes')->myFindaAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        $pagination->setTemplate('ApplicationMyNotesBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationMyNotesBundle:Notes:indextodolist.html.twig', array(
                    'pagination' => $pagination,
                ));
    }
    
      public function indextodoaAction()
    {
        $em = $this->getDoctrine()->getManager();
    $entities = $em->getRepository('ApplicationMyNotesBundle:Notes')->myFindaAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        $pagination->setTemplate('ApplicationMyNotesBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationMyNotesBundle:Notes:indextodolista.html.twig', array(
                    'pagination' => $pagination,
                ));
    }
    public function indexstickyAction() {
        
         $em = $this->getDoctrine()->getManager();
        $user_id= $this->getuserid();
        
        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'notes_sticky');
        

        
        
        $entities = $em->getRepository('ApplicationMyNotesBundle:Notes')-> myFindamoi($user_id);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1)/* page number */, 20/* limit per page */
        );
        $pagination->setTemplate('ApplicationMyNotesBundle:pagination:sliding.html.twig');
        return $this->render('ApplicationMyNotesBundle:Notes:indexsticky.html.twig', array(
                    'pagination' => $pagination,
                ));
    }

    /* ============================================================
* Update position xyz Fonction
* ============================================================ */

    //resizable
    /*
public function resizableAction() {
$table = new Application_Model_DbTable_Notes();
$params = $this->_request->getParams();
// if (isset($id)&& $id>0){
$id = (int) $params['id'];
$where['id = ?'] = $id;
$w = (int) $params['w'];
$h = (int) $params['h'];
$data['wh'] = $w . "x" . $h;
$table->update($data, $where);
return;
}
*/
    public function updateposAction() {


        $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $id = '';
            /* $applis = array();
$cert_app = array();
*/
            $id = $request->request->get('id');
            $x = $request->request->get('x');
            $y = $request->request->get('y');
            $z = $request->request->get('z');
            $w = $request->request->get('w');
            $h = $request->request->get('h');
            $text=$request->request->get('datatext');
            
            $note_entity = $em->getRepository('ApplicationMyNotesBundle:Notes')->find($id);
            if (!$note_entity) {
                throw $this->createNotFoundException('Unable to find Notes entity.');
            }
            $data = array();
            $data['id'] = $id;
             if (isset($x) && isset($y) && isset($z)){
                 //if (array_key_exists('xyz',$data)){
           
           // if (isset( $data['xyz'])){
                    $data['xyz'] = $x . 'x' . $y . 'x' . $z;
           $note_entity->setXyz($data['xyz']);
          }
          if (isset($w) && isset($h)){
                    $data['wh'] = $w . 'x' . $h;
                    $note_entity->setWh($data['wh']);
            }
            if (isset($text)){
                    $data['text'] = $text;
                    $note_entity->setText($text);
            }
            $em->persist($note_entity);
            $em->flush();
   
            $output = array();
            $response = new Response();
            $output[] = array('success' => true);
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode($output));
            return $response;

            /* $applis=array(3,4);
$response = new Response(json_encode($output));
$response->headers->set('Content-Type', 'application/json');

return $response; */
       /* } else {
$response = new Response();
$output[] = array('success' => false);
$response->headers->set('Content-Type', 'application/json');
$response->setContent(json_encode($output));
return $response;
}*/
        }


        
    }

    /* return $this->render('ApplicationMyNotesBundle:Notes:edit.html.twig', array(
'entity' => $entity,
'edit_form' => $editForm->createView(),
'delete_form' => $deleteForm->createView(),
));
} */
    /* $this->view->addHelperPath(
"ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
$layout = Zend_Layout::getMVCInstance();
$table = new Application_Model_DbTable_Notes();
$params = $this->_request->getParams();
$id = (int) $params['id'];
$where['id = ?'] = $id;
$x = (int) $params['x'];
$y = (int) $params['y'];
$z = (int) $params['z'];
$data['xyz'] = $x . "x" . $y . "x" . $z;
$table->update($data, $where);
return;
} */
    /*
public function updatesnapAction() {
$this->view->addHelperPath(
"ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
$layout = Zend_Layout::getMVCInstance();
$table = new Application_Model_DbTable_Notes();
$params = $this->_request->getParams();
$id = (int) $params['id'];
$where['id = ?'] = $id;
$x = (int) $params['x'];
$y = (int) $params['y'];
$z = (int) $params['z'];
$w = (int) $params['w'];
$h = (int) $params['h'];
$data['classement'] = $params['classement'];
if ($w != 0 && $h != 0)
$data['wh'] = $w . "x" . $h;
$data['xyz'] = $x . "x" . $y . "x" . $z;
$table->update($data, $where);
return;
}

*/

    private function mypager($adapter = null, $max = 5, $page = 1) {
        if (isset($adapter)) {
            $pagerfanta = new Pagerfanta($adapter);
            $pagerfanta->setMaxPerPage(5);

            return $pagerfanta;
        } else {
            return null;
        }
    }

    public function indexgenemuAction() {

// parameters to template
        $entity = new Notes();
        $form = $this->createForm(new NotesType(), $entity);

        return $this->render('ApplicationMyNotesBundle:Notes:indexgenemu.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));


//return compact('pagination');
    }

    //==============================================
    // VIEW ALL ACTEURS
    //==============================================
    public function viewfantaAction($page = null) {
        /* Automatique
$request = $this->get('request');
$page = $request->query->get('page',1);
*/
        $em = $this->container->get('doctrine')->getManager();
        $repo = $em->getRepository('ApplicationMyNotesBundle:Notes')->myFindAll();
        //
        // $entityQuery = $em->getRepository('MyAppFilmothequeBundle:Acteur')->myXFindAll();
        $adapter = new DoctrineORMAdapter($repo);
        $pagerfanta = $this->mypager($adapter);
        try {
            $pagerfanta->setCurrentPage($page);
            $q = $pagerfanta->getCurrentPageResults();
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }
        return $this->container->get('templating')->renderResponse(
                        'ApplicationMyNotesBundle:Notes:index_fanta.html.twig', array(
                    'pagerfanta' => $pagerfanta,
                    'entities' => $q,
                ));
    }

    //==============================================
    // VIEW ALL ACTEURS
    //==============================================
    public function viewapyAction($page = 1) {

        // $em = $this->container->get('doctrine')->getManager();
        // $source = $em->getRepository('ApplicationMyNotesBundle:Notes');

        $source = new Entity('ApplicationMyNotesBundle:Notes');
        // Get a Grid instance
        // $grid = new Grid('grid');
        $grid = $this->container->get('grid');
        // Attach the source to the grid
        $grid->setSource($source);
        $grid->setDefaultOrder('id', 'desc');
        // $grid->addExport(new XMLExport('XML Export', 'export'));
        // Set the selector of the number of items per page
        $grid->setLimits(array(5, 10, 15));

        // Set the default page
        $grid->setPage(1);
        $grid->addMassAction(new DeleteMassAction());
        // action column
        $actionsColumn = new ActionsColumn('info_column_1', 'Actions 1');
        $actionsColumn->setSeparator("<br />");
//$grid->addColumn($actionsColumn);
        // Add row actions in the default row actions column
        // $myRowAction = new RowAction('Edit', 'notes_edit');

        $myRowAction = new RowAction('', 'notes_edit', true, '_self', array('class' => 'editme'));

        $myRowAction->setColumn('info_column1');
        $grid->addRowAction($myRowAction);

        $myRowAction = new RowAction('Delete', 'notes_delete', true, '_self');
        $grid->addRowAction($myRowAction);
        // Return the response of the grid to the template
        return $grid->getGridResponse('ApplicationMyNotesBundle:Notes:indexapy.html.twig');
    }

    /**
* Finds and displays a Notes entity.
*
*/
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationMyNotesBundle:Notes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationMyNotesBundle:Notes:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
* Displays a form to create a new Notes entity.
*
*/
    public function newAction() {
        $entity = new Notes();
        $form = $this->createForm(new NotesType(), $entity);

        return $this->render('ApplicationMyNotesBundle:Notes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
* Creates a new Notes entity.
*
*/
    public function createAction(Request $request) {
        
            
        $entity = new Notes();
        $form = $this->createForm(new NotesType(), $entity);
        $form->bind($request);
        $user_id = $this->getuserid();
        //echo "id=$user_id<br>";
        //exit(1);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $current_user = $em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
            $entity->setProprietaire($current_user);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('notes_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationMyNotesBundle:Notes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
* Displays a form to edit an existing Notes entity.
*
*/
    public function editAction($id) {
        
         $request = $this->getRequest();
     $em = $this->getDoctrine()->getManager();
       
        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
        }
        else {
        $entity = $em->getRepository('ApplicationMyNotesBundle:Notes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notes entity.');
        }

        $editForm = $this->createForm(new NotesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationMyNotesBundle:Notes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }
    }

    /**
* Edits an existing Notes entity.
*
*/
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationMyNotesBundle:Notes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new NotesType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('notes_edit', array('id' => $id)));
        }

        return $this->render('ApplicationMyNotesBundle:Notes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    
     //==============================================
   
    /**
* Deletes a Notes entity.
*
*/
      //==============================================
    // SUPPRIMER ACTEUR
    //==============================================
    public function ajaxdeleteAction() {
       $request = $this->getRequest();

        $output=array();
         $response = new Response();
         if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $id = $request->request->get('id');
           // echo "id=$id<br>";
           // $classement = $request->request->get('classement');
                $note_entity = $em->getRepository('ApplicationMyNotesBundle:Notes')->find($id);
            if (!$note_entity) {
                throw $this->createNotFoundException('Unable to find Notes entity.');
            }
         
            $em->remove($note_entity);
            $em->flush();
              $output[] = array('success' => true);
            
         }else { $output[] = array('success' => false);}
       
        
         $response->headers->set('Content-Type', 'application/json');
         $response->setContent(json_encode($output));
        return $response;
    }
    //==============================================
    // SUPPRIMER ACTEUR
    //==============================================
    public function deleteAction($id) {
       $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
             $id = $request->request->get('id');
             $note_entity = $em->getRepository('ApplicationMyNotesBundle:Notes')->find($id);
            if (!$note_entity) {
                throw $this->createNotFoundException('Unable to find Notes entity.');
            }
         
            $em->remove($note_entity);
            $em->flush();
        
            $output = array();
            $response = new Response();
            $output[] = array('success' => true);
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode($output));
            return $response;

       
           
        }

      else {
         $em = $this->container->get('doctrine')->getManager();
        $note = $em->find('ApplicationMyNotesBundle:Notes', $id);
        if (!$note) {
            throw new NotFoundHttpException("Note non trouvée");
        }
        $em->remove($note);
        $em->flush();
        return $this->redirect($this->generateUrl('notes'));
    }
    }



    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

     /* ====================================================================
*
* RECUP USER_ID ET GROUP_ID
*
* =================================================================== */

    private function getuserid() {


        $em = $this->getDoctrine()->getManager();
        $user_security = $this->container->get('security.context');
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($user_security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $user_id = $user->getId();
        } else {
            $user_id = 0;
          
        }

        return ($user_id);
       
    }
}


