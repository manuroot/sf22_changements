<?php

namespace Application\CalendarBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\MappedSuperclass
 */
abstract class BaseAdesignCalendar {

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     * @GRID\Column(type="text",field="title", title="Titre",size="12")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=50, nullable=true)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="bgcolor", type="string", length=50, nullable=false)
     */
    protected $bgColor;

    /**
     * @var string
     *
     * @ORM\Column(name="fgcolor", type="string", length=50, nullable=false)
     */
    protected $fgColor;

    /**
     * @var string
     *
     * @ORM\Column(name="cssclass", type="string", length=50, nullable=true)
     */
    protected $cssClass;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=false)
      @GRID\Column(title="Start", size="40",format="Y-m-d h:i",type="datetime")
     */
    protected $startDatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=false)
     *  @GRID\Column(title="end", size="40",format="Y-m-d h:i",type="datetime")
     */
    protected $endDatetime;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_date", type="datetime", nullable=false)
     */
    protected $addedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime", nullable=false)
     */
    protected $updatedDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="allday", type="boolean", nullable=true)
     */
    protected $allDay = false;

    public function __construct($title, \DateTime $startDatetime, \DateTime $endDatetime = null, $allDay = false) {
        $this->title = $title;
        $this->startDatetime = $startDatetime;
        $this->addedDate = new \DateTime('now');
        $this->updatedDate = new \DateTime('now');
        $this->setAllDay($allDay);
        if ($endDatetime === null && $this->allDay === false) {
            throw new \InvalidArgumentException("Must specify an event End DateTime if not an all day event.");
        }

        $this->endDatetime = $endDatetime;
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

    /**
     * Convert calendar event details to an array
     *
     * @return array $event
     */
    public function toArray() {
        $event = array();

        if ($this->id !== null) {
            $event['id'] = $this->id;
        }

        $event['title'] = $this->title;
        $event['start'] = $this->startDatetime->format("Y-m-d\TH:i:sP");

        if ($this->url !== null) {
            $event['url'] = $this->url;
        }

        if ($this->bgColor !== null) {
            $event['backgroundColor'] = $this->bgColor;
            $event['borderColor'] = $this->bgColor;
        }

        if ($this->fgColor !== null) {
            $event['textColor'] = $this->fgColor;
        }

        if ($this->cssClass !== null) {
            $event['className'] = $this->cssClass;
        }

        if ($this->endDatetime !== null) {
            $event['end'] = $this->endDatetime->format("Y-m-d\TH:i:sP");
        }

        $event['allDay'] = $this->allDay;

        return $event;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setBgColor($color) {
        $this->bgColor = $color;
    }

    public function getBgColor() {
        return $this->bgColor;
    }

    public function setFgColor($color) {
        $this->fgColor = $color;
    }

    public function getFgColor() {
        return $this->fgColor;
    }

    public function setCssClass($classcss) {
        $this->cssClass = $classcss;
    }

    public function getCssClass() {
        return $this->cssClass;
    }

    public function setStartDatetime(\DateTime $start) {
        $this->startDatetime = $start;
    }

    public function getStartDatetime() {
        return $this->startDatetime;
    }

    public function setEndDatetime(\DateTime $end) {
        $this->endDatetime = $end;
    }

    public function getEndDatetime() {
        return $this->endDatetime;
    }

    public function setAllDay($allDay = false) {
        //   echo "allday en entree entity=--" . $allDay . "--";
        if ($allDay === 'true' || $allDay === true) {
            //  echo "TRUE";
            $this->allDay = true;
        } else {
            $this->allDay = false;
            //   echo "FALSE";
        }
        //  $this->allDay = (boolean) $allDay;
    }

    public function getAllDay() {
        return $this->allDay;
    }

    /**
     * Set addedDate
     *
     * @param \DateTime $addedDate
     * @return CertificatsCenter
     */
    public function setAddedDate($addedDate) {
        $this->addedDate = $addedDate;

        return $this;
    }

    /**
     * Get addedDate
     *
     * @return \DateTime 
     */
    public function getAddedDate() {
        return $this->addedDate;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     * @return CertificatsCenter
     */
    public function setUpdatedDate($updatedDate) {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * Get updatedDate
     *
     * @return \DateTime 
     */
    public function getUpdatedDate() {
        return $this->updatedDate;
    }

}
