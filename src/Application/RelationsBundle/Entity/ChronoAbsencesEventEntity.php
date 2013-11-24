<?php
namespace Application\RelationsBundle\Entity;

use Application\CalendarBundle\Entity\EventEntity;
/**
 * Class for holding a calendar event's details.
 * 
 * @author Mike Yudin <mikeyudin@gmail.com>
 */

class ChronoAbsencesEventEntity extends EventEntity
{
 
     /**
     * @var string
     *
     */
    protected $user;
       
      /**
     * @var integer
     *
     */
    protected $userid;
    
      /**
     * @var string
     *
     */
    protected $description;
    
    
      
    public function __construct( $title, \DateTime $startDatetime, \DateTime $endDatetime = null, $allDay = false ) {
     parent :: __construct( $title, $startDatetime, $endDatetime , $allDay  );
     //   $this->chromosome = $chromosome;
    }
   
    public function setUser($user) {
        $this->user = $user;
    }
  
    public function getUser() {
        return $this->user;
    }
     public function setUserId($userid) {
        $this->userid = $userid;
    }
  
    public function getUserId() {
        return $this->userid;
    }
      public function setDescription($description) {
        $this->description = $description;
    }
  
    public function getDescription() {
        return $this->description;
    }
    
   /* public function setTypeabsence($typeabsence) {
        $this->typeabsence = $typeabsence;
    }
  
    public function getTypeabsence() {
        return $this->typeabsence;
    }*/
    public function toArray()
    {
     $event=parent::toArray();
      $event['user'] = $this->user;
       $event['userid'] = $this->userid;
       $event['origine'] = 'absence';
    //  $event['absence'] = $this->typeabsence;
     /*if ($this->user != null) {
            $event['user'] = $this->user;
        }
        var_dump($event);*/
        return $event;
    }
}
