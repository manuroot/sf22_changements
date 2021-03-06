<?php


namespace Application\ChangementsBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;
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

        $companyEvents = $this->entityManager->getRepository('ApplicationChangementsBundle:Changements')
                          ->createQueryBuilder('company_events')
                          ->where('company_events.event_datetime BETWEEN :startDate and :endDate')
                          ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
                          ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
                          ->getQuery()->getResults();


        foreach($companyEvents as $companyEvent) {

            // create an event with a start/end time, or an all day event
            if ($companyEvent->getAllDayEvent() === false) {
                $eventEntity = new EventEntity($companyEvent->getTitle(), $companyEvent->getStartDatetime(), $companyEvent->getEndDatetime());
            } else {
                $eventEntity = new EventEntity($companyEvent->getTitle(), $companyEvent->getStartDatetime(), null, true);
            }

            //optional calendar event settings
            $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
            $eventEntity->setBgColor('#FF0000'); //set the background color of the event's label
            $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label
            $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels

            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
}