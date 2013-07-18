<?php

namespace Application\CertificatsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\CertificatsBundle\Entity\CertificatsFiles;
use Application\CertificatsBundle\Form\CertificatsFilesType;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/*use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Export\ExcelExport;
 *  
 */

/*
 use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
 * 
 */
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;



/**
 * CertificatsFiles controller.
 *
 */
class CertificatsFilesController extends Controller
{

    /* ====================================================================
     * 
     *  CREATION DU PAGINATOR
     * 
      =================================================================== */
    private function createpaginator($query, $num_perpage = 5) {

        $paginator = $this->get('knp_paginator');
        $pagename = 'page'; // Set custom page variable name
        $page = $this->get('request')->query->get($pagename, 1); // Get custom page variable

        $pagination = $paginator->paginate(
                $query, $page, $num_perpage, array('pageParameterName' => $pagename,
            "sortDirectionParameterName" => "dir",
            'sortFieldParameterName' => "sort")
        );
        $pagination->setTemplate('ApplicationCertificatsBundle:pagination:twitter_bootstrap_pagination.html.twig');
        return $pagination;
    }
    
    
    /**
     * Lists all CertificatsFiles entities.
     *
     */
    public function indexoldAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->findAll();

        return $this->render('ApplicationCertificatsBundle:CertificatsFiles:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    
    
      public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('buttonretour', 'certificats_documents');
         $query = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->myFindAll();
          $pagination = $this->createpaginator($query, 10);
        $count = $pagination->getTotalItemCount();
          return $this->render('ApplicationCertificatsBundle:CertificatsFiles:index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }
    /**
     * Creates a new CertificatsFiles entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new CertificatsFiles();
        $form = $this->createForm(new CertificatsFilesType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('certificats_documents_show', array('id' => $entity->getId())));
        }

        return $this->render('ApplicationCertificatsBundle:CertificatsFiles:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new CertificatsFiles entity.
     *
     */
    public function newAction()
    {
        $entity = new CertificatsFiles();
        $form   = $this->createForm(new CertificatsFilesType(), $entity);

        return $this->render('ApplicationCertificatsBundle:CertificatsFiles:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CertificatsFiles entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsFiles entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:CertificatsFiles:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CertificatsFiles entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsFiles entity.');
        }

        $editForm = $this->createForm(new CertificatsFilesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ApplicationCertificatsBundle:CertificatsFiles:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing CertificatsFiles entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CertificatsFiles entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CertificatsFilesType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('certificats_documents_edit', array('id' => $id)));
        }

        return $this->render('ApplicationCertificatsBundle:CertificatsFiles:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CertificatsFiles entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CertificatsFiles entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('certificats_documents'));
    }

    /**
     * Creates a form to delete a CertificatsFiles entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
     
  /* ====================================================================
     * 
     *  DOWNLOAD CERT
     * 
     * @Secure(roles="ROLE_ADMIN")
      =================================================================== */
      
      public function downloadAction($id) {
         
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationCertificatsBundle:CertificatsFiles')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docchangements entity.');
        }
        
        $request = $this->get('request');
     //   $url='docchangements';
        $session = $request->getSession();
        $url=$session->get('buttonretour');
        if (!isset($url))
            $url='certificatscenter';    
        $filename=$entity->getPath();
        $realname=$entity->getOriginalFilename();
        if (!isset($realname))
            $realname=$filename;
        $path = $this->get('kernel')->getRootDir() . "/../" . $entity->getDownloadDir();
        if (!file_exists($path . $filename)) {
              // $session->getFlashBag()->add('error', "Le fichier $path/$filename n 'existe pas (code 1)");
            $session->getFlashBag()->add('error', "Le fichier $filename n 'existe pas (code 1)");
            return $this->redirect($this->generateUrl($url));
        }

        try {
            $content = file_get_contents($path . $filename);
        } catch (\ErrorException $e) {
            $session->getFlashBag()->add('error', "Le fichier $filename n 'existe pas (code 2)");
            return $this->redirect($this->generateUrl($url));
        }
         $response = new Response();

        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $realname);
        $session->getFlashBag()->add('notice', "Le fichier $filename a ete téléchargé");

        $response->setContent($content);
        return $response;
    }
}
