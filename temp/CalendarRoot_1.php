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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class for holding a calendar event's details.
 *
 * @author Mike Yudin <mikeyudin@gmail.com>
 */

/**
 * Changements
 *
 * @ORM\Table(name="wdcalendar_root")
 * @UniqueEntity(fields="nom", message="Ce nom existe déjà...")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Application\CalendarBundle\Repository\AdesignCalendarRootRepository")
 */
class CalendarRoot {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    protected $nom;

     /**
     * @var integer
     *
     * @ORM\Column(name="plage", type="integer", length=2, nullable=false)
     */
    protected $plage;
    
    
     /**
     * @var integer
     *
     * @ORM\Column(name="starthour", type="integer", length=2, nullable=false)
     */
    protected $startHour;
    
     /**
     * @var integer
     *
     * @ORM\Column(name="endhour", type="integer", length=2, nullable=false)
     */
    protected $endHour;
    
    
     /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;
    
    
    /**
     * not proprietaire side (mappedby)
     * @var ArrayCollection $categories
     * @ORM\OneToMany(targetEntity="CalendarEvenements", mappedBy="rootcalendar",cascade={"persist"})
     */
    private $categories;
     /**
     * @var \Application\Sonata\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\OrderBy({"username" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner", nullable=true,referencedColumnName="id")
     * })
     */
    private $owner;
    
    
     /**
     * @var string
     * 
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="wdcalendar_root_groupes",
     * joinColumns={@ORM\JoinColumn(name="calendarroot_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="groupe_id", referencedColumnName="id")}
     * )
     */
    protected $groupedit;
    
    
    

    public function __construct() {
        $this->groupedit = new ArrayCollection();
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }
public function getPlage() {
        return $this->plage;
    }

    public function setPlage($plage=30) {
        $this->plage = $plage;
    }
   
    public function getStartHour() {
        return $this->startHour;
    }

    public function setStartHour($hour=7) {
        $this->startHour = $hour;
    }
    
     public function getEndHour() {
        return $this->endHour;
    }

    public function setEndHour($hour=17) {
        $this->endHour = $hour;
    }
    
    
      /**
     * Set description
     *
     * @param string $description
     * @return Environnement
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

   

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    
    /**
     * Add categories
     *
     * @param \Application\CalendarBundle\Entity\CalendarEvenements $categories
     * @return CalendarRoot
     */
    public function addCategorie(\Application\CalendarBundle\Entity\CalendarEvenements $categories) {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Application\CalendarBundle\Entity\CalendarEvenements $categories
     */
    public function removeCategorie(\Application\CalendarBundle\Entity\CalendarEvenements $categories) {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories() {
        return $this->categories;
    }
    
/**
     * Set owner
     *
     * @param \Application\Sonata\UserBundle\Entity\User $owner
     * @return Epost
     */
    public function setOwner(\Application\Sonata\UserBundle\Entity\User $owner = null) {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getOwner() {
        return $this->owner;
    }
    
    

    /**
     * Add groupedit
     *
     * @param \Application\Sonata\UserBundle\Entity\Group $groupedit
     * @return CalendarRoot
     */
    public function addGroupedit(\Application\Sonata\UserBundle\Entity\Group $groupedit)
    {
         if (!$this->groupedit->contains($groupedit)) {

            $this->groupedit->add($groupedit);
        }
     //   $this->groupedit[] = $groupedit;
    
     //   return $this;
    }

    /**
     * Remove groupedit
     *
     * @param \Application\Sonata\UserBundle\Entity\Group $groupedit
     */
    public function removeGroupedit(\Application\Sonata\UserBundle\Entity\Group $groupedit)
    {
         if (!$this->groupedit->contains($groupedit)) {
            return;
        }
        $this->groupedit->removeElement($groupedit);
    }

    /**
     * Get groupedit
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroupedit()
    {
        return $this->groupedit;
    }
    
    public function setGroupedit($groupedit) {
        if ($groupedit instanceof ArrayCollection || is_array($groupedit)) {
            foreach ($groupedit as $item) {
                $this->addGroupedit($item);
                
            }
        } elseif ($groupedit instanceof \Application\Sonata\UserBundle\Entity\Group) {
            $this->addGroupedit($groupedit);
        } else {
            throw new \Exception("$groupedit must be an instance of User or ArrayCollection");
        }
    }
}