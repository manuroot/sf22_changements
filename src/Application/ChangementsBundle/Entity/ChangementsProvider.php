<?php


namespace Application\ChangementsBundle\Entity;
use CalendR\Event\Provider\ProviderInterface;

class ChangementsProvider implements ProviderInterface
{
    public function getEvents(\DateTime $begin, \DateTime $end, array $options = array())
    {
        /*
         Returns an array of events here. $options is the second argument of
         $factory->getEvents(), so you can filter your event on anything (Calendar id/slug ?)
        */
    }
}