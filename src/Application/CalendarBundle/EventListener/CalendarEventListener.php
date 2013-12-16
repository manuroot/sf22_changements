<?php

namespace Application\CalendarBundle\EventListener;

// src/Acme/DemoBundle/EventListener/CalendarEventListener.php  

use Application\CalendarBundle\Event\CalendarEvent;
use Application\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class CalendarEventListener {

    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function loadEvents(CalendarEvent $calendarEvent) {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();
        $rootId = $calendarEvent->getRootId();
        $values = array('a,b, COUNT(c.id) AS num');
        $query = $this->entityManager->getRepository('ApplicationCalendarBundle:AdesignCalendar')
                ->createQueryBuilder('a')
                ->select($values)
                ->leftJoin('a.picture', 'c')
                ->leftJoin('a.calendarid', 'b')
                ->where('a.startDatetime BETWEEN :startDate and :endDate')
                ->orwhere('a.endDatetime BETWEEN :startDate and :endDate')
                ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
                ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
        ;

        if (isset($rootId)) {
            $query->andwhere('b.id = :idRoot')
                    ->setParameter('idRoot', $rootId);
        }
        $query->groupby('a.id');
        $companyEvents = $query->getQuery()->getScalarResult();
  //var_dump($companyEvents);
        foreach ($companyEvents as $v) {
            $eventEntity = new EventEntity($v['a_title'], $v['a_startDatetime'], $v['a_endDatetime'], $v['a_allDay']
            );
            $eventEntity->setId($v['a_id']);
            $eventEntity->setFgColor($v['a_fgColor']);
            $eventEntity->setBgColor($v['a_bgColor']);
            $eventEntity->setCssClass($v['a_cssClass']);
            $nbfic=(isset($v['num']) ? $v['num'] : "0");
             $eventEntity->setNbfiles($nbfic);
            $eventEntity->setDescription($v['a_description']); //set the foreground color of the event's label

            $calendarEvent->addEvent($eventEntity);
            ;
        }
      //  var_dump($companyEvents);
      //  var_dump($eventEntity);
      //  var_dump($calendarEvent);
        //     var_dump($companyEvents);
        /*   foreach ($companyEvents as $companyEvent) {
          $eventEntity = new EventEntity(
          $companyEvent['title'],

          // $companyEvent->getStartDatetime(),
          //  $companyEvent->getEndDatetime(),
          //   $companyEvent->getAllDay()
          );
          } */

        /*
          $companyEvents=$query->getQuery()->getResult();

          foreach ($companyEvents as $companyEvent) {
          $eventEntity = new EventEntity(
          $companyEvent->getTitle(),
          $companyEvent->getStartDatetime(),
          $companyEvent->getEndDatetime(),
          $companyEvent->getAllDay()
          );
          } */
    }

    public function loadEvents1(CalendarEvent $calendarEvent) {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();
        $rootId = $calendarEvent->getRootId();

        //  echo "rootid=$rootId";

        /*
          $query = $this->createQueryBuilder('a')
          ->select($values)
          //   ->distinct('a.id')
          ->leftJoin('a.rootcalendar', 'b');
         */
        $values = array('a,b,c');
        // $values = array('a,b,count(if(c.id IS NOT NULL,1,NULL)) as cid');
        $query = $this->entityManager->getRepository('ApplicationCalendarBundle:AdesignCalendar')
                ->createQueryBuilder('a')
                ->select($values)
                ->leftJoin('a.picture', 'c')
                ->leftJoin('a.calendarid', 'b')
                ->where('a.startDatetime BETWEEN :startDate and :endDate')
                ->orwhere('a.endDatetime BETWEEN :startDate and :endDate')
                ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
                ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))

        //->orderBy('a.id')
        // ->getQuery()->getResult();
        ;

        if (isset($rootId)) {
            $query->andwhere('b.id = :idRoot')
                    ->setParameter('idRoot', $rootId);
            // $parameters['idRoot'] = $id_root;
            //$query->setParameters($parameters);
        }
        $companyEvents = $query->getQuery()->getResult();

        foreach ($companyEvents as $companyEvent) {
            $eventEntity = new EventEntity(
                    $companyEvent->getTitle(), $companyEvent->getStartDatetime(), $companyEvent->getEndDatetime(), $companyEvent->getAllDay()
            );
            //    var_dump($eventEntity);

            $className = $companyEvent->getCssClass();
            //   
            //optional calendar event settings
            $id = $companyEvent->getId();
            $description = $companyEvent->getDescription();
            //  echo "id $id allday=" .   $companyEvent->getAllDay() . "\n";
            $bg = $companyEvent->getBgColor();
            $fg = $companyEvent->getFgColor();
            //  $eventEntity->setId($id); // default is false, set to true if this is an all day event
            $eventEntity->setId($id); // default is false, set to true if this is an all day event
            // $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
            $eventEntity->setBgColor($bg); //set the background color of the event's label
            $eventEntity->setCssClass($className); //set the background color of the event's label
            $eventEntity->setDescription($description); //set the foreground color of the event's label

            $eventEntity->setFgColor($fg); //set the foreground color of the event's label
            /*  $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked */
            //$eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
            //finally, add the event to the CalendarEvent for displaying on the calendar
            //  $eventEntity['description']=$companyEvent->getDescription();
            $calendarEvent->addEvent($eventEntity);
        }
    }

    public function loadEventsch(CalendarEvent $calendarEvent) {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();
        //  echo "here\n";
        $values = array('DISTINCT a,partial f.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description}');
        $companyEvents = $this->entityManager->getRepository('ApplicationChangementsBundle:Changements')
                        ->createQueryBuilder('a')
                        ->select($values)
                        ->leftJoin('a.idProjet', 'f')
                        ->leftJoin('a.demandeur', 'c')
                        ->leftJoin('a.idStatus', 'd')
                        ->where('a.dateDebut BETWEEN :startDate and :endDate')
                        ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
                        ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
                        ->orderBy('a.id')
                        ->getQuery()->getResult();


        foreach ($companyEvents as $companyEvent) {
            //   var_dump($companyEvents);
            $nom = $companyEvent->getNom();
            $id = $companyEvent->getId();
            $d = $companyEvent->getDateDebut();
            $f = $companyEvent->getDateFin();
            $projet = $companyEvent->getIdProjet();
            $status_num = $companyEvent->getIdStatus()->getId();
            //   echo "status=$status_num";
            if (!$f)
                $f = $d;
            $nickname = $nom . " (" . $projet . ')';
            $eventEntity = new EventEntity($nickname, $d, $f);
            $eventEntity->setCssClass("class" . $status_num);
            $eventEntity->setId($id); // default is false, set to true if this is an all day event

            $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label

            $calendarEvent->addEvent($eventEntity);
        }
    }

    public function loadEventsCalendar(CalendarEvent $calendarEvent) {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();
        //  echo "here\n";
        $values = array('DISTINCT a,partial f.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description}');
        $companyEvents = $this->entityManager->getRepository('ApplicationChangementsBundle:Changements')
                        ->createQueryBuilder('a')
                        ->select($values)
                        ->leftJoin('a.idProjet', 'f')
                        ->leftJoin('a.demandeur', 'c')
                        ->leftJoin('a.idStatus', 'd')
                        ->where('a.dateDebut BETWEEN :startDate and :endDate')
                        ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
                        ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
                        ->orderBy('a.id')
                        ->getQuery()->getResult();


        foreach ($companyEvents as $companyEvent) {
            //   var_dump($companyEvents);
            $nom = $companyEvent->getNom();
            $id = $companyEvent->getId();
            $d = $companyEvent->getDateDebut();
            $f = $companyEvent->getDateFin();
            $projet = $companyEvent->getIdProjet();
            $status_num = $companyEvent->getIdStatus()->getId();
            //   echo "status=$status_num";
            if (!$f)
                $f = $d;
            $nickname = $nom . " (" . $projet . ')';
            $eventEntity = new EventEntity($nickname, $d, $f);
            $eventEntity->setCssClass("class" . $status_num);
            $eventEntity->setId($id); // default is false, set to true if this is an all day event

            $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label

            $calendarEvent->addEvent($eventEntity);
        }
    }

}
