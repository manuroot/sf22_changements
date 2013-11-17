<?php

namespace Application\ChangementsBundle\EventListener;

// src/Acme/DemoBundle/EventListener/CalendarEventListener.php  

use Application\ChangementsBundle\Event\CalendarEvent;
use Application\ChangementsBundle\Entity\EventEntity;
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

        // load events using your custom logic here,
        // for instance, retrieving events from a repository

        $companyEvents = $this->entityManager->getRepository('ApplicationChangementsBundle:AdesignCalendar')
                        ->createQueryBuilder('a')
                        ->where('a.startDatetime BETWEEN :startDate and :endDate')
                        ->orwhere('a.endDatetime BETWEEN :startDate and :endDate')
                        //getEndDatetime
                        //   ->where('company_events.event_datetime BETWEEN :startDate and :endDate')
                        ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
                        ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
                        /* ->orderBy('a.startDatetime') */
                        ->orderBy('a.id')
                        ->getQuery()->getResult();


        foreach ($companyEvents as $companyEvent) {

            //  echo "id=" . $companyEvent->getId();
            //   echo "id=" . $companyEvent->getTitle();
            // var_dump($companyEvent->getAllDayEvent());
            // create an event with a start/end time, or an all day event
            
            // non, on utilise maintenant
         /*   if ($companyEvent->getAllDay() === false) {
                $eventEntity = new EventEntity($companyEvent->getTitle(), $companyEvent->getStartDatetime(), $companyEvent->getEndDatetime());
            } else {
                $eventEntity = new EventEntity($companyEvent->getTitle(), $companyEvent->getStartDatetime(), null, true);
            }*/

            
            $eventEntity = new EventEntity(
                    $companyEvent->getTitle(), 
                    $companyEvent->getStartDatetime(), 
                    $companyEvent->getEndDatetime(),
                    $companyEvent->getAllDay()
                    );
         //   var_dump($eventEntity);
            
            $className = $companyEvent->getCssClass();
  //   
            //optional calendar event settings
            $id = $companyEvent->getId();
             $description=$companyEvent->getDescription();
          //  echo "id $id allday=" .   $companyEvent->getAllDay() . "\n";
            //  $bg=$companyEvent->getBgColor();
          //  $eventEntity->setId($id); // default is false, set to true if this is an all day event
            $eventEntity->setId($id); // default is false, set to true if this is an all day event
            // $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
            // $eventEntity->setBgColor($bg); //set the background color of the event's label
            $eventEntity->setCssClass($className); //set the background color of the event's label
            $eventEntity->setDescription($description); //set the foreground color of the event's label
            $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label
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
                     //   ->createQueryBuilder('a')
                        ->where('a.dateDebut BETWEEN :startDate and :endDate')
                        //   ->where('company_events.event_datetime BETWEEN :startDate and :endDate')
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
            $projet=$companyEvent->getIdProjet();
            $status_num=$companyEvent->getIdStatus()->getId();
         //   echo "status=$status_num";
             if (!$f)
                $f = $d;
           // $status=$companyEvent->getIdStatus();
            /*$nickname= $nom . "\n(" . $projet . ')';*/
            $nickname= $nom . " (" . $projet . ')';
             $eventEntity = new EventEntity($nickname, $d, $f);
          /*   echo "status=$status\n";*/
            
          //   if ()
             $eventEntity->setCssClass("class" . $status_num);
           /* if ($status == 'open')
              $eventEntity->setCssClass('class1'); //set the background color of the event's label
                elseif ($status == 'closed')
                    $eventEntity->setCssClass('class2'); //set the background color of the event's label
              elseif ($status == 'en preparation')
                $eventEntity->setCssClass('class0'); //set the background color of the event's label
               elseif ($status == 'REPORTE')
              $eventEntity->setCssClass('class3'); //set the background color of the event's label
              else
                  $eventEntity->setCssClass('class4'); //set the background color of the event's label*/
         //   echo "id =$id nom=$nom status=$status";
           /* if ($d)
 <div class='external-event class1' myclass="class1">Ouvert</div>
<div class='external-event class2' myclass="class2">Fermé</div>
<div class='external-event class0' myclass="class0">En préparation</div>
<div class='external-event class3' myclass="class3">Reporté</div>
<div class='external-event class4' myclass="class4">Annulé</div>    
          /*  else
                echo " f=--" . $f->format('Y-m-d H:i:s') . "--\n";
*/

           
            $eventEntity->setId($id); // default is false, set to true if this is an all day event
          
            $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label
            
            $calendarEvent->addEvent($eventEntity);

        }
    }

}