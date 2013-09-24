<?php

namespace Application\ChangementsBundle\Manager;

use Application\ChangementsBundle\Manager\ChangementsBaseManager;
use Doctrine\ORM\EntityManager;
use Application\ChangementsBundle\Entity\Changements;

class ChangementsManager extends ChangementsBaseManager {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function loadChangement($changementId) {
          $entity = $this->getRepository()->myFindaIdAll($changementId);
         if (!$entity ) {
            throw new NotFoundHttpException($this->get('translator')->trans('Ce changement n\'existe pas'));
        }
        else {
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
    
    public function getRepository() {
        return $this->em->getRepository('ApplicationChangementsBundle:Changements');
    }

}