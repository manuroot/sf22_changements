<?php

namespace Application\CentralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Application\ChangementsBundle\Entity\Changements;
use Application\RelationsBundle\Entity\Document;




class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ApplicationCentralBundle:Default:index.html.twig', array('name' => $name));
    }
      // A ameliorer: recup par l'entity !!!

    public function downloadAction($filename) {
        $request = $this->get('request');
     //   $url='docchangements';
        $session = $request->getSession();
        $url=$session->get('buttonretour');
        if (!isset($url))
            $url='docchangements';    
        
        $path = $this->get('kernel')->getRootDir() . "/../web/uploads/documents/";

        // Flush in "safe" mode to enforce an Exception if keys are not unique

        if (!file_exists($path . $filename)) {
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
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename);
        //$session = $this->getRequest()->getSession();
        $session->getFlashBag()->add('notice', "Le fichier $filename a ete téléchargé");

        $response->setContent($content);
        return $response;
    }

}
