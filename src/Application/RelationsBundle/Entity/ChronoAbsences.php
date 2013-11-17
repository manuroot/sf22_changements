<?php


namespace Application\RelationsBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;


/**
 * ChronoUser
 *
 * @ORM\Table(name="chrono_absences")
 * @ORM\Entity(repositoryClass="Application\RelationsBundle\Repository\ChronoAbsencesRepository")
 */

class ChronoAbsences
{
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
     * @var \ChronoUser
     * One direction
     * @ORM\ManyToOne(targetEntity="Application\RelationsBundle\Entity\ChronoUser")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;
    
    
  
    public function __toString() {
        return $this->getNom();    // this will not look good if SonataAdminBundle uses this ;)
    }
    
   

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
     * Set nom
     *
     * @param string $nom
     * @return ChronoAbsences
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
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
     * @return ChronoAbsences
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
     * Set user
     *
     * @param \Application\RelationsBundle\Entity\ChronoUser $user
     * @return ChronoAbsences
     */
    public function setUser(\Application\RelationsBundle\Entity\ChronoUser $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\RelationsBundle\Entity\ChronoUser 
     */
    public function getUser()
    {
        return $this->user;
    }
}