<?php

namespace Application\ChangementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\ChangementsBundle\Entity\Changements;
use Application\RelationsBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\ChangementsBundle\Form\ChangementsType;
use Application\ChangementsBundle\Form\ChangementsFilesForEntityType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Changements controller.
 *
 */
class ChangementsUploadController extends Controller {
    
 private function check_retour() {
     $session = $this->getRequest()->getSession();
     $btn_retour = $session->get('buttonretour');
            if ($btn_retour == 'changements_fanta' || $btn_retour == 'changements_myfanta')
                return $this->redirect($this->generateUrl($btn_retour));
            else
                return $this->redirect($this->generateUrl('changements_fanta'));
 }
    /**
     * Displays a form to edit an existing Changements entity.
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editentityfilesAction($id) {
          $entity = $this->get('changement.common.manager')->loadChangement($id);
           $editForm = $this->createForm(new ChangementsFilesForEntityType(), $entity);
        //return  $this->check_retour();
        return $this->render('ApplicationChangementsBundle:Changements:editentityfiles.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    public function editpunkaveAction($id) {
      $entity = $this->get('changement.common.manager')->loadChangement($id);
       $editForm = $this->createForm(new ChangementsFilesForEntityType(), $entity);
       //return $this->check_retour();
           $isNew = true;
        $editId = $this->getRequest()->get('editId');
        if (!preg_match('/^\d+$/', $editId)) {
            $editId = sprintf('%09d', mt_rand(0, 1999999999));
            if ($entity->getId()) {
                $this->get('punk_ave.file_uploader')->syncFiles(
                        array('from_folder' => 'attachments/' . $entity->getId(),
                            'to_folder' => 'tmp/attachments/' . $editId,
                            'create_to_folder' => true));
            }
        }
       $existingFiles = $this->get('punk_ave.file_uploader')
                ->getFiles(array('folder' => 'tmp/attachments/' . $editId));
        return $this->render('ApplicationChangementsBundle:Changements:edit-punkave.html.twig', array(
                    'entity' => $entity,
                    'form' => $editForm->createView(),
                    'editId' => $editId,
                    'existingFiles' => $existingFiles,
                    'isNew' => $isNew,
        ));
    }

    
    /**
     * Edits an existing Changements entity.
     * sauvegarde du changement avec les fichiers updatés
     * 
     * @Secure(roles="ROLE_USER")
     */
    public function updateentityfilesAction(Request $request, $id) {
       $em = $this->get('changement.common.manager');
       $entity=$em->checkandloadChangement($id);
       $editForm = $this->createForm(new ChangementsFilesForEntityType(), $entity);
       $editForm->bind($request);
        if ($request->getMethod() == 'POST') {
            $em->saveChangement($entity);
            $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");
           return $this->check_retour();
        }
        return $this->render('ApplicationChangementsBundle:Changements:editentityfiles.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /*
     * JSON AJAX
     */

    public function punkvalidateuploadAction() {

        //echo "HERE";
        $editId = $this->getRequest()->get('editId');
        if (!preg_match('/^\d+$/', $editId)) {
            throw new Exception("Bad edit id");
        }

        $this->get('punk_ave.file_uploader')->handleFileUpload(array(
            'folder' => 'tmp/attachments/' . $editId,
            'allowed_extensions' => array('png', 'jpg', 'doc', 'docx', 'zip', 'rar', 'tar')
        ));
        //     $this->get('punk_ave.file_uploader')->handleFileUpload(array('folder' => 'tmp/attachments/' . $editId));



        /* return $this->render('ApplicationChangementsBundle:Changements:edit-punkave.html.twig', array(
          'posting' => $posting,
          'form' => $editForm->createView(),
          'editId' => $editId,
          )); */
    }

    /**
     * Displays a form to create a new Docchangements entity.
     *
     */

    /**
     * 
     *
     * @Secure(roles="ROLE_USER")
     */
    public function newFichierAction($id) {
 
     
        $entity=$this->get('changement.common.manager')->loadChangement($id);
        $editForm = $this->createForm(new ChangementsType(), $entity);
        return $this->render('ApplicationChangementsBundle:Changements:new_fichier.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Edits an existing Changements entity.
     *
     * @Secure(roles="ROLE_USER")
     */
    public function punkaveupdateAction(Request $request, $id) {

        $request = $this->getRequest();

        $alldatas = $request->request->all();
                     $entity=$this->get('changement.common.manager')->loadChangement($id);
        $editId = $this->getRequest()->get('editId');
        if (!preg_match('/^\d+$/', $editId)) {
            $editId = sprintf('%09d', mt_rand(0, 1999999999));
            if ($entity->getId()) {
                $this->get('punk_ave.file_uploader')->syncFiles(
                        array('from_folder' => 'attachments/' . $entity->getId(),
                            'to_folder' => 'tmp/attachments/' . $editId,
                            'create_to_folder' => true));
            }
        }

        $fileUploader = $this->get('punk_ave.file_uploader');
        $fileUploader->syncFiles(
                array('from_folder' => '/tmp/attachments/' . $editId,
                    'to_folder' => '/attachments/' . $entity->getId(),
                    'remove_from_folder' => true,
                    'create_to_folder' => true));
        /*
          $deleteForm = $this->createDeleteForm($id);
          $editForm = $this->createForm(new ChangementsType(), $entity);
          $editForm->bind($request);

          if ($editForm->isValid()) {
          $this->get('changement.common.manager')->saveChangement($entity);
          $session = $this->getRequest()->getSession();
          $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");

          // ajoute des messages flash
          $btn_retour = $session->get('buttonretour');
          if ($btn_retour == 'changements_fanta' || $btn_retour == 'changements_myfanta')
          return $this->redirect($this->generateUrl($btn_retour));
          else
          return $this->redirect($this->generateUrl('changements_fanta'));
          }
         */
           $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");
          // return $this->check_retour();
        
        return $this->render('ApplicationChangementsBundle:Changements:edit.html.twig', array(
                    'entity' => $entity,
                        //'edit_form' => $editForm->createView(),
                        //  'delete_form' => $deleteForm->createView(),
        ));
    }

   

    /**
     * Deletes a Changements entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $this->get('changement.common.manager')->deleteChangement($id);
        }
        return $this->redirect($this->generateUrl('changements_posttest'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * @Template()
     */
    public function uploadAction() {
        $document = new Document();
        $form = $this->createFormBuilder($document)
                ->add('name')
                ->add('file')
                ->getForm()
        ;

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {


                $em = $this->getDoctrine()->getManager();
                $fic = $document->generateFilename();
                echo "$fic<br>";
                exit(1);
                // $document->upload();
                $em->persist($document);
                $em->flush();
            }
        }
        return $this->render('ApplicationChangementsBundle:Changements:upload.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function oneupuploaderAction() {
        return $this->render('ApplicationChangementsBundle:Changements:index-uploader.html.twig', array(
        ));
    }

}

