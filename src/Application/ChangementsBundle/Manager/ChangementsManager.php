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
        return $this->getRepository()->myFindaIdAll($changementId);
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
        $changement=$this->loadChangement($changementId);
        removeAndFlush($entity);
        $this->removeAndFlush($changement);
    }
    
    
    public function getRepository() {
        return $this->em->getRepository('ApplicationChangementsBundle:Changements');
    }

}