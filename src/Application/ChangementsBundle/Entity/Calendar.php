<?php

namespace Application\ChangementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;
use Application\RelationsBundle\Entity\Environnements;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use CalendR\Event\AbstractEvent;


/**
 * Changements
 *
 * @ORM\Table(name="wdcalendar_main")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Application\ChangementsBundle\Repository\CalendarRepository")
 */

class Calendar {

//class Changements


    protected $begin;
    protected $end;
    protected $uid;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @GRID\Column(title="id", size="10", type="text",filterable="false")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     * @ORM\OrderBy({"nom" = "ASC"})
     * @Assert\Length(
     *      min = "5",
     *      max = "50",
     *      minMessage = "Your name must be at least {{ limit }} characters length |
     *  Au minimum {{ limit }} caracteres",
     *      maxMessage = "Your first name cannot be longer than than {{ limit }} characters length |
     *  Au maximum {{ limit }} caracteres"
     * )
     *
     * @GRID\Column(field="nom", title="Nom",size="80")
     */
    private $nom;

   /**
     * @ORM\Column(name="color", type="string", length=20, nullable=true)
     */
    private $color;
     
     
     
   /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=false)
     * @GRID\Column(title="Début", size="30",format="Y-m-d",type="datetime")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     * @GRID\Column(title="Fin", size="30",format="Y-m-d",type="datetime")
     * 
     */
    private $dateFin;
    // @GRID\Column(title="Fin", size="40",format="Y-m-d h:i",type="datetime")
   

    /**
     * @var boolean
     *
     * @ORM\Column(name="IsAllDayEvent", type="boolean", nullable=true)
     */
    private $IsAllDayEvent;
    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=100, nullable=true)
     */
    private $location;
 
       /**
     * @var string
     *
     * @ORM\Column(name="RecurringRule", type="string", length=100, nullable=true)
     */
    private $RecurringRule;
    
    
    
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Changements
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom() {
        return $this->nom;
    }

    
     /**
     * Set color
     *
     * @param string $color
     */
    
    public function setColor($color) {
        $this->color = $color;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getColor() {
        return $this->color;
    }
    
    
    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Changements
     */
    public function setDateDebut($dateDebut) {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut() {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Changements
     */
    public function setDateFin($dateFin) {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin() {
        return $this->dateFin;
    }

   
   
    /**
     * Constructor
     */
    public function __construct() {
        // ????????
     /*   $this->dateDemande = new \DateTime('now');*/
            $this->color = "red";
    
    }

 
    public function __toString() {
        return $this->getNom();    // this will not look good if SonataAdminBundle uses this ;)
    }

  

    public function getUid() {
        return $this->id;
    }

    public function getBegin() {
        return $this->dateDebut;
    }

    public function getEnd() {
        if (!isset($this->dateFin))
            return $this->dateDebut;
        else
            return $this->dateFin;
    }

  
    
   


    /**
     * Set IsAllDayEvent
     *
     * @param boolean $isAllDayEvent
     * @return Calendar
     */
    public function setIsAllDayEvent($isAllDayEvent)
    {
        $this->IsAllDayEvent = $isAllDayEvent;
    
        return $this;
    }

    /**
     * Get IsAllDayEvent
     *
     * @return boolean 
     */
    public function getIsAllDayEvent()
    {
        return $this->IsAllDayEvent;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Calendar
     */
    public function setLocation($location)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set RecurringRule
     *
     * @param string $recurringRule
     * @return Calendar
     */
    public function setRecurringRule($recurringRule)
    {
        $this->RecurringRule = $recurringRule;
    
        return $this;
    }

    /**
     * Get RecurringRule
     *
     * @return string 
     */
    public function getRecurringRule()
    {
        return $this->RecurringRule;
    }
      /**
     * Set description
     *
     * @param string $description
     * @return Changements
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }
}