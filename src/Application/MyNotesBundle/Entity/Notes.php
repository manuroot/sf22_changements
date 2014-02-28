<?php

namespace Application\MyNotesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
* Notes
*
* @ORM\Table(name="notes_main")
* @GRID\Source(columns="id,text,name,xyz,dt,wh,proprio,classement,categories.nom,color.nom")
* @ORM\Entity(repositoryClass="Application\MyNotesBundle\Entity\NotesRepository")
*/

class Notes
{
    /**
* @var integer
*
* @ORM\Column(name="id", type="integer", nullable=false)
* @GRID\Column(title="id", size="50", type="text")
* @ORM\Id
* @ORM\GeneratedValue(strategy="IDENTITY")
*/
    private $id;

    /**
* @var string
*
* @ORM\Column(name="text", type="string", length=128, nullable=false)
*/
    private $text;

    /**
* @var string
*
* @ORM\Column(name="name", type="string", length=60, nullable=false)
*/
    private $name;

    /**
* @var string
*
* @ORM\Column(name="xyz", type="string", length=20, nullable=false)
*/
    private $xyz;

    /**
* @var \DateTime
*
* @ORM\Column(name="dt", type="datetime", nullable=false)
* @GRID\Column(title="Dt", size="150", type="date",filter="select")
*/
    private $dt;

    /**
* @var string
*
* @ORM\Column(name="wh", type="string", length=20, nullable=false)
*/
    private $wh;

    /**
* @var \Application\Sonata\UserBundle\Entity\User
*
* @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
* @ORM\OrderBy({"username" = "ASC"})
* @ORM\JoinColumns({
* @ORM\JoinColumn(name="proprietaire", referencedColumnName="id")
* })
*/
    private $proprietaire;


    /**
* @var string
*
* @ORM\Column(name="classement", type="string", length=8, nullable=false)
*/
    private $classement;

    /**
* @var \NotesCategories
*
* @ORM\ManyToOne(targetEntity="NotesCategories")
* @ORM\JoinColumns({
* @ORM\JoinColumn(name="categories", referencedColumnName="id")
* })
*
* @GRID\Column(field="categories.nom", title="Cat Nom")
*/
    private $categories;

    /**
* @var \NotesColor
*
* @ORM\ManyToOne(targetEntity="NotesColor")
* @ORM\JoinColumns({
* @ORM\JoinColumn(name="color", referencedColumnName="id")
* })
*
* @GRID\Column(field="color.nom", title="Couleur")
*/
    private $color;


    /**
* Constructor
*/
    public function __construct() {
        // $this->history = new \Doctrine\Common\Collections\ArrayCollection();
    
        $this->setDt(new \DateTime());
       $this->setClassement("0");
       $this->setWh("200x200");
       $this->setXyz("200x200x200");
       
    }

    
    
    //@ORM\PreUpdate
    /*
public function setUpdatedAtValue() {
$this->setUpdatedAt(new \DateTime());
// $this->setUpdatedAt(new \DateTime());
$this->setSlug($this->getName());
// reclaculer la note globale ??
}*/

    
    
    
    
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
* Set text
*
* @param string $text
* @return Notes
*/
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
* Get text
*
* @return string
*/
    public function getText()
    {
        return $this->text;
    }

    /**
* Set name
*
* @param string $name
* @return Notes
*/
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
* Get name
*
* @return string
*/
    public function getName()
    {
        return $this->name;
    }

    /**
* Set xyz
*
* @param string $xyz
* @return Notes
*/
    public function setXyz($xyz)
    {
        $this->xyz = $xyz;
    
        return $this;
    }

    /**
* Get xyz
*
* @return string
*/
    public function getXyz()
    {
        return $this->xyz;
    }

    /**
* Set dt
*
* @param \DateTime $dt
* @return Notes
*/
    public function setDt($dt)
    {
        $this->dt = $dt;
    
        return $this;
    }

    /**
* Get dt
*
* @return \DateTime
*/
    public function getDt()
    {
        return $this->dt;
    }

    /**
* Set wh
*
* @param string $wh
* @return Notes
*/
    public function setWh($wh)
    {
        $this->wh = $wh;
    
        return $this;
    }

    /**
* Get wh
*
* @return string
*/
    public function getWh()
    {
        return $this->wh;
    }
    
    
      /**
* Set proprietaire
*
* @param \Application\Sonata\UserBundle\Entity\User $proprietaire
* @return Eproduit
*/
    public function setProprietaire(\Application\Sonata\UserBundle\Entity\User $proprietaire = null) {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    /**
* Get proprietaire
*
* @return \Application\Sonata\UserBundle\Entity\User
*/
    public function getProprietaire() {
        return $this->proprietaire;
    }

    /**
* Set classement
*
* @param string $classement
* @return Notes
*/
    public function setClassement($classement)
    {
        $this->classement = $classement;
    
        return $this;
    }

    /**
* Get classement
*
* @return string
*/
    public function getClassement()
    {
        return $this->classement;
    }

    /**
* Set categories
*
* @param \Application\MyNotesBundle\Entity\NotesCategories $categories
* @return Notes
*/
    public function setCategories(\Application\MyNotesBundle\Entity\NotesCategories $categories = null)
    {
        $this->categories = $categories;
    
        return $this;
    }

    /**
* Get categories
*
* @return \Application\MyNotesBundle\Entity\NotesCategories
*/
    public function getCategories()
    {
        return $this->categories;
    }

    /**
* Set color
*
* @param \Application\MyNotesBundle\Entity\NotesColor $color
* @return Notes
*/
    public function setColor(\Application\MyNotesBundle\Entity\NotesColor $color = null)
    {
        $this->color = $color;
    
        return $this;
    }

    /**
* Get color
*
* @return \Application\MyNotesBundle\Entity\NotesColor
*/
    public function getColor()
    {
        return $this->color;
    }
}