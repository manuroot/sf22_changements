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

    public function __construct() {
        
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

}