<?php

namespace Application\ChangementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * CalendarEvenements
 *
 * @ORM\Table(name="wdcalendar_evenements")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Application\ChangementsBundle\Repository\CalendarEvenementsRepository")
 */

class CalendarEvenements {
  /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=40, nullable=false)
     */
    private $nom;

     /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="cssclass", type="string", length=50, nullable=true)
     */
    protected $cssClass;
    
  
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomProjet
     *
     * @param string $nom
     * @return Environnement
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

     public function __toString() {
        return $this->getNom();    // this will not look good if SonataAdminBundle uses this ;)
    }

    /**
     * Get nomapplis
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
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
     * Constructor
     */
    public function __construct()
    {
     
    }
    
    
     public function setCssClass($classcss)
    {
        $this->cssClass = $classcss;
    }
    
    public function getCssClass()
    {
        return $this->cssClass;
    }
    
}