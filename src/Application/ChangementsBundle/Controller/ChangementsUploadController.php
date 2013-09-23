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


//use Application\ChangementsBundle\Entity\GridExport;

//use Doctrine\ORM\Tools\Pagination\CountOutputWalker;
//use Application\ChangementsBundle\Entity\ChangementsStatus;
use JMS\SecurityExtraBundle\Annotation\Secure;
//use Application\CentralBundle\Model\MesFiltresBundle;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/* use Pagerfanta\Pagerfanta;
  use Pagerfanta\Adapter\DoctrineORMAdapter;
  use Pagerfanta\Exception\NotValidCurrentPageException; */

/**
 * Changements controller.
 *
 */
class ChangementsUploadController extends Controller {
    

    /**
     * Displays a form to edit an existing Changements entity.
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editentityfilesAction($id) {
        $em = $this->getDoctrine()->getManager();

        // $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);
        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindaIdAll($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        }
        $editForm = $this->createForm(new ChangementsFilesForEntityType(), $entity);

        $session = $this->getRequest()->getSession();
        $session->set('buttonretour', 'changements_fanta');
        return $this->render('ApplicationChangementsBundle:Changements:editentityfiles.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    public function editpunkaveAction($id) {
        $em = $this->getDoctrine()->getManager();

        // $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);
        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindaIdAll($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        }

        $editForm = $this->createForm(new ChangementsFilesForEntityType(), $entity);
        $session = $this->getRequest()->getSession();
        $btn_retour = $session->get('buttonretour');
        if ($btn_retour != 'changements_fanta' && $btn_retour == 'changements_myfanta')
            $session->set('buttonretour', 'changements_fanta');


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
        // print_r($existingFiles);exit(1);

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
     *
     * @Secure(roles="ROLE_USER")
     */
    public function updateentityfilesAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        }

        $editForm = $this->createForm(new ChangementsFilesForEntityType(), $entity);

        $editForm->bind($request);
        if ($request->getMethod() == 'POST') {
            echo "entity a updater: $id<br>";
            // $alldatas = $request->request->all();
            // $uploadedFile = $request->files->get('fileschangements');
            //  $datas = $alldatas["changements_searchfilter"];
            /* print_r($alldatas);
              print_r($uploadedFile);

              exit(1); */
            $em->persist($entity);
            $em->flush();
            $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('warning', "Enregistrement $id update successfull");

            // ajoute des messages flash
            $btn_retour = $session->get('buttonretour');
            if ($btn_retour == 'changements_fanta' || $btn_retour == 'changements_myfanta')
                return $this->redirect($this->generateUrl($btn_retour));
            else
                return $this->redirect($this->generateUrl('changements_fanta'));
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

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->myFindaIdAll($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        }
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
        //    $datas = $alldatas["changements_searchfilter"];
        //$uploadedFile = $request->files->all();
        //  print_r($uploadedFile);
        //   print_r($alldatas);exit(1);
        //  $datas = $alldatas["changements_searchfilter"];
        /* print_r($alldatas);
          print_r($uploadedFile);
          print_r($alldatas);exit(1); */
        //    $parameters = $datas;

        if (!$entity = $this->get('changement.common.manager')->loadChangement($id)) {
            throw new NotFoundHttpException($this->get('translator')->trans('This desk does not exist.'));
        }
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
        return $this->render('ApplicationChangementsBundle:Changements:edit.html.twig', array(
                    'entity' => $entity,
                        //'edit_form' => $editForm->createView(),
                        //  'delete_form' => $deleteForm->createView(),
        ));
    }

   

//updateentityfiles
    /**
     * Deletes a Changements entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationChangementsBundle:Changements')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Changements entity.');
            }

            $em->remove($entity);
            $em->flush();
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

