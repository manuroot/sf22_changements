<?php
namespace Application\ADesigns\CalendarBundle\Entity;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class for holding a calendar event's details.
 * 
 * @author Mike Yudin <mikeyudin@gmail.com>
 */


/**
 * Mycalendar
 *
 * @ORM\Table(name="calendar_main")
 * @ORM\Entity(repositoryClass="Application\ADesignsCalendarBundle\Repository\MyEventsCalendarRepository")
 */


class MyEventEntity extends EventEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @var string Title/label of the calendar event.
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    protected $title;
    
    /**
     * @var string URL Relative to current path.
     * @ORM\Column(name="url", type="string", length=50, nullable=false)
     */
    protected $url;
    
    /**
     * @var string HTML color code for the bg color of the event label.
     * @ORM\Column(name="bgcolor", type="string", length=20, nullable=true)
     */
    protected $bgColor;
    
    /**
     * @var string HTML color code for the foregorund color of the event label.
      * @ORM\Column(name="fgcolor", type="string", length=20, nullable=true)
     */
    protected $fgColor;
    
    /**
     * @var string css class for the event label
     * @ORM\Column(name="cssclass", type="string", length=20, nullable=true) 
     */
    protected $cssClass;
    
    /**
     * @var \DateTime DateTime object of the event start date/time.
      * @ORM\Column(name="date_start", type="datetime", nullable=false)
     */
    protected $startDatetime;
    
    /**
     * @var \DateTime DateTime object of the event end date/time.
     * @ORM\Column(name="date_end", type="datetime", nullable=false)
     */
    protected $endDatetime;
    
    /**
     * @var boolean Is this an all day event?
     * @ORM\Column(name="allday", type="boolean", nullable=true)
     */
    protected $allDay = false;
    
    public function __construct($title, \DateTime $startDatetime, \DateTime $endDatetime = null, $allDay = false)
    {
        $this->title = $title;
        $this->startDatetime = $startDatetime;
        $this->setAllDay($allDay);
        
        if ($endDatetime === null && $this->allDay === false) {
            throw new \InvalidArgumentException("Must specify an event End DateTime if not an all day event.");
        }
        
        $this->endDatetime = $endDatetime;
    }
    
}
