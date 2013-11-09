<?php
namespace Application\ChangementsBundle\EventListener;
// src/Acme/DemoBundle/EventListener/CalendarEventListener.php  

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class CalendarEventListener
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();

        // load events using your custom logic here,
        // for instance, retrieving events from a repository

        $companyEvents = $this->entityManager->getRepository('ApplicationChangementsBundle:AdesignCalendar')
                          ->createQueryBuilder('a')
                         ->where('a.startDatetime BETWEEN :startDate and :endDate')
              //   ->where('company_events.event_datetime BETWEEN :startDate and :endDate')
                          ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
                          ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
                           ->getQuery()->getResult();


      foreach($companyEvents as $companyEvent) {

        //  echo "id=" . $companyEvent->getId();
       //   echo "id=" . $companyEvent->getTitle();
           // var_dump($companyEvent->getAllDayEvent());
            // create an event with a start/end time, or an all day event
            if ($companyEvent->getAllDay() === false) {
                $eventEntity = new EventEntity($companyEvent->getTitle(), $companyEvent->getStartDatetime(), $companyEvent->getEndDatetime());
            } else {
                $eventEntity = new EventEntity($companyEvent->getTitle(), $companyEvent->getStartDatetime(), null, true);
            }

           //optional calendar event settings
            $id=$companyEvent->getId($id);
            $bg=$companyEvent->getBgColor();
            $eventEntity->setId($id); // default is false, set to true if this is an all day event
           // $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
            $eventEntity->setBgColor($bg); //set the background color of the event's label
            $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label
          /*  $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked*/
            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels

            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
}