<?php

namespace Application\CalendarBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use  Application\CalendarBundle\Entity\EventEntity;

/**
 * Event used to store EventEntitys
 * 
 * @author Mike Yudin <mikeyudin@gmail.com>
 */
class CalendarEvent extends Event
{
    const CONFIGURE = 'calendar.load_events';
    const CONFIGUREA = 'calendar.load_eventsch';

    private $startDatetime;
    
    private $endDatetime;
    
    private $events;
     private $rootId;
    
    /**
     * Constructor method requires a start and end time for event listeners to use.
     * 
     * @param \DateTime $start Begin datetime to use
     * @param \DateTime $end End datetime to use
     */
    public function __construct(\DateTime $start, \DateTime $end,$root_id=null)
    {
        $this->startDatetime = $start;
        $this->endDatetime = $end;
         $this->rootId = $root_id;
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getEvents()
    {
        return $this->events;
    }
    
    /**
     * If the event isn't already in the list, add it
     * 
     * @param EventEntity $event
     * @return CalendarEvent $this
     */
    public function addEvent(EventEntity $event)
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
        }
        
        return $this;
    }
    
    public function getStartDatetime()
    {
        return $this->startDatetime;
    }

    public function getEndDatetime()
    {
        return $this->endDatetime;
    }
      public function getRootId()
    {
        return $this->rootId;
    }

}