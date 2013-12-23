<?php

namespace Application\ChangementsBundle\Manager;

use Application\ChangementsBundle\Manager\ChangementsBaseManager;
use Doctrine\ORM\EntityManager;
use Application\ChangementsBundle\Entity\Changements;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChangementsManager extends ChangementsBaseManager {

    protected $em;
    protected $securityContext;

    public function __construct(EntityManager $em, SecurityContextInterface $securityContext) {
        $this->em = $em;
        $this->securityContext = $securityContext;
    }

    public function loadChangement($changementId) {
        $entity = $this->getRepository()->myFindaIdAll($changementId);
        if (!$entity) {
            throw new NotFoundHttpException($this->get('translator')->trans('Ce changement n\'existe pas'));
        } else {
            return $entity;
        }
    }

    public function checkandloadChangement($changementId) {
        
        $entity = $this->getRepository()->find($changementId);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Changements entity.');
        } else {
            return $entity;
        }
    }

    /**
     * Save Changements entity
     *
     * @param Changements $changements
     */
    public function saveChangement(Changements $changements) {
        $this->persistAndFlush($changements);
    }

    /**
     * Save Changements entity
     *
     * @param Changements $changements
     */
    public function deleteChangement($changementId) {
        $changement = $this->checkandloadChangement($changementId);

        $this->removeAndFlush($changement);
    }

    /*
      public function OpeAjax($term,$field) {
      $entity = $this->getRepository()->findAjaxValue(array($field => $term));;
      //$entity_ticket = $em->getRepository('ApplicationChangementsBundle:Changements')->findAjaxValue(array($field => $term));
      $json = array();
      foreach ($entity->getQuery()->getResult() as $ticket) {
      array_push($json, $ticket->getTicketExt());
      }
      return $json;
      }
     */

     public function refresh(Changements $changements) {
        $this->em->refresh($changements);
    }
    public function getRepository() {
        return $this->em->getRepository('ApplicationChangementsBundle:Changements');
    }

    public function mygetusers($changementId) {
        $changement = $this->checkandloadChangement($changementId);



        foreach ($changement->getIdUsers() as $u) {

            //  echo "id=--" . (string) $u->getEmail() . "-- <br>";
            echo "id=--" . $u->getEmail() . "-- <br>";
        }
        return;
    }

    public function sendemailChangement($id) {
        
        $changement = $this->checkandloadChangement($id);
       // $this->refresh($changement);
        if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->securityContext->getToken()->getUser();
            $user_id = $user->getId();
            $demandeur=$user->getUsername();
        }else {return false;}
        // Recup entity user connected
        $user = $this->em->getRepository('ApplicationSonataUserBundle:User')->find($user_id);
        if (!$user) {
            throw new NotFoundHttpException('user connected not found.');
        }


      //  $demandeur = $user->getUsername();
     //   echo "demandeur=$demandeur<br>";
        //  $users = $entity->getIdusers();
        $title = $changement->getNom();
        $subject = "Création d'une demande de changement" . ": " . $title;
       // echo "subject=$subject<br>";

        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('manuel.rottereau@pc-supervision.fr');
                //->setFrom($demandeur . '@pc-supervision.fr');
        $send_users = $changement->getIdusers();
        $setto='manuel.rottereau@sesam-vitale.fr';
      //  echo "setto=$setto";
         $message->setTo($setto);
        
       /* foreach ($send_users as $u) {
            $message->setTo($u->getEmail());
            echo "id=" . $u->getId() . "-- ";
            echo "mailto=--" . (string) $u->getEmail() . "-- <br>";
        }*/

   /*     echo "<br>";
echo "---------End<br>";
*/
        return $message;
    }

}
