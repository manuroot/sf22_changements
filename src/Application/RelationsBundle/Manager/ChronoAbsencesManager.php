<?php

namespace Application\RelationsBundle\Manager;

use Application\RelationsBundle\Manager\ChronoAbsencesBaseManager;
use Doctrine\ORM\EntityManager;
use Application\RelationsBundle\Entity\ChronoAbsences;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChronoAbsencesManager extends ChronoAbsencesBaseManager {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function loadAbsence($absenceId) {
          $entity = $this->getRepository()->myFindId($absenceId);
         if (! $entity ) {
            throw new NotFoundHttpException($this->get('translator')->trans('Cet absence n\'existe pas'));
        }
        else {
            return $entity;
        }
      
    }

    public function checkandloadAbsence($absenceId) {
        $entity = $this->getRepository()->find($absenceId);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Absence entity.');
        } else {
            return $entity;
        }
    }

    /**
     * Save Changements entity
     *
     * @param Changements $changements
     */
    public function saveAbsence(ChronoAbsences $absence) {
        $this->persistAndFlush($absence);
    }

    /**
     * Save Changements entity
     *
     * @param ChronoAbsences $absence
     */
    public function deleteAbsence($absenceId) {
        $absence = $this->checkandloadAbsence($absenceId);
        
        $this->removeAndFlush($absence);
    }
    
    public function getRepository() {
        return $this->em->getRepository('ApplicationRelationsBundle:ChronoAbsences');
    }

}