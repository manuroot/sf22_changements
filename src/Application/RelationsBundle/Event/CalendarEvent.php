<?php

namespace Application\RelationsBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use  Application\CalendarBundle\Entity\EventEntity;
use Application\CalendarBundle\Event\CalendarEvent;

/**
 * Event used to store EventEntitys
 * 
 * @author Mike Yudin <mikeyudin@gmail.com>
 */
class AbsencesEvent extends CalendarEvent
{
  
    const CONFIGUREC = 'calendar.load_eventsabsences';

  
    
   
}