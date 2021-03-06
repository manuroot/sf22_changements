<?php

namespace Application\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;
use Doctrine\Common\Collections\ArrayCollection;
use Application\CalendarBundle\Model\BaseAdesignCalendar;


/**
 * Changements
 *
 * @ORM\Table(name="wdcalendar_adesignmain")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Application\CalendarBundle\Repository\AdesignCalendarRepository")
 */


class AdesignCalendar extends BaseAdesignCalendar
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
     * @var \Calendarrootid
     * 
     * @ORM\ManyToOne(targetEntity="\Application\CalendarBundle\Entity\CalendarRoot")
     * @ORM\OrderBy({"nom" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="calendarid", referencedColumnName="id")
     * })
     */
    private $calendarid;
    
    
        /**
     * @var string
     * 
     * @ORM\ManyToMany(targetEntity="DocCalendar", inversedBy="idcalendar",cascade={"persist"})
     * @ORM\JoinTable(name="wdcalendar_j_documents",
     * joinColumns={@ORM\JoinColumn(name="calendar_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id")}
     * )
     */
    protected $picture;
    
     public function __construct($title, \DateTime $startDatetime, \DateTime $endDatetime = null, $allDay = false)
    {
         parent::__construct($title,$startDatetime,$endDatetime, $allDay);
         $this->picture = new ArrayCollection();
         
    }

     public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set calendarid
     *
     * @param \Application\CalendarBundle\Entity\CalendarRoot $calendarid
     * @return AdesignCalendar
     */
    public function setCalendarid(\Application\CalendarBundle\Entity\CalendarRoot $calendarid = null)
    {
        $this->calendarid = $calendarid;
    
        return $this;
    }

    /**
     * Get calendarid
     *
     * @return \Application\CalendarBundle\Entity\CalendarRoot 
     */
    public function getCalendarid()
    {
        return $this->calendarid;
    }
    
      /**
     * Get picture
     *
     * @return Docchangements 
     */
    public function getPicture() {
        return $this->picture;
    }

    public function getNbPicture() {
        return count($this->picture);
    }
    
    /**
     * Add picture
     *
     * @param Docchangements $picture
     * @return Changements
     */
    public function addPicture(DocCalendar $picture) {
        // Si l'objet fait déjà partie de la collection on ne l'ajoute pas
        if (!$this->picture->contains($picture)) {
            $this->picture->add($picture);
        }
    }

    /**
     * Set picture
     *
     * @param Docchangements $picture
     * @return Changements
     */
    // public function setPicture(Docchangements $picture = null)
    public function setPicture($items) {
        if ($items instanceof ArrayCollection || is_array($items)) {
            foreach ($items as $item) {
                $this->addPicture($item);
            }
        } elseif ($items instanceof DocCalendar) {
            $this->addPicture($item);
        } else {
            throw new \Exception("$items must be an instance of Applus or ArrayCollection");
        }
    }

    public function initclonePicture() {
        $this->picture = new ArrayCollection();
    }
/**
     * Remove picture
     *
     * @param \Application\CalendarBundle\Entity\DocCalendar $picture
     */

    /**
     * Remove idapplis
     *
     * @param Docchangements $picture
     */
    public function removePicture(DocCalendar $picture) {
        if (!$this->picture->contains($picture)) {
            return;
        }
        $this->picture->removeElement($picture);

        $picture->removeIdcalendar($this);
        //removeIdchangement(\Application\ChangementsBundle\Entity\Changements $idchangement) {
    }


}