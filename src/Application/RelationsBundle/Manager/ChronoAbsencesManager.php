<?php

namespace Application\RelationsBundle\Manager;

use Application\RelationsBundle\Manager\ChronoAbsencesBaseManager;
use Doctrine\ORM\EntityManager;
use Application\RelationsBundle\Entity\ChronoAbsences;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


use Application\CalendarBundle\Event\CalendarEvent;
//use Application\CalendarBundle\Entity\EventEntity;
use Application\RelationsBundle\Entity\ChronoAbsencesEventEntity;

//use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;


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

     public function loadChronoCalendar(CalendarEvent $calendarEvent) {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();
        $values = array('DISTINCT a,partial c.{id,nomUser}');
           $absences = $this->getRepository()
              ->createQueryBuilder('a')
                        ->select($values)
                        ->leftJoin('a.user', 'c')
                        ->where('a.dateDebut BETWEEN :startDate and :endDate')
                        ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
                        ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
                        ->orderBy('a.id')
                        ->getQuery()->getResult();

           // 1) on remplit les chaque event avec chaque absence
           // 2) on lie chaque event au calendarevent
        foreach ($absences as $absence) {
            //   var_dump($companyEvents);
            $nom = $absence->getNom();
            $id = $absence->getId();
            $d = $absence->getDateDebut();
            $f = $absence->getDateFin();
            $allday=$absence->getAllDay();
            $user = $absence->getUser()->getNomUser();
            if (!$f)
                $f = $d;
            $nickname = ucfirst($user) . ": " .$nom ;
            //$eventEntity = new EventEntity($nickname, $d, $f);
//( $title, \DateTime $startDatetime, \DateTime $endDatetime = null, $allDay = false ) {
  
            $eventEntity = new ChronoAbsencesEventEntity($nickname, $d, $f,$allday);
            $eventEntity->setCssClass("class1");
            $eventEntity->setId($id); // default is false, set to true if this is an all day event
            $eventEntity->setUser($user); //set the foreground color of the event's label
            $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label

           // $eventEntity['rere']="trtrr";
            $calendarEvent->addEvent($eventEntity);
           //$calendarEvent->events['rrr']="reere";
        }
        
       $return_events = array();

        foreach ($calendarEvent->getEvents() as $event) {
            $return_events[] = $event->toArray();
          //  var_dump($event->toArray());
        }
        
        return $return_events;
        //var_dump($return_events);
        
        
    }
}