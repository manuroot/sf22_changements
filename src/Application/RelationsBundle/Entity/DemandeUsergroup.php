<?php


namespace Application\RelationsBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * ChronoUsergroup
 *
 * @ORM\Table(name="demande_usergroup")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\RelationsBundle\Repository\DemandeUsergroupRepository")
 */
class DemandeUsergroup
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
     * @var \ChronoUserGroup
     *
     * @ORM\ManyToOne(targetEntity="Application\RelationsBundle\Entity\EserviceGroup",inversedBy="users", cascade={"persist", "merge"}))
     * @ORM\OrderBy({"nomGroup" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_group", referencedColumnName="id",nullable=false)
     * })
     * @ORM\OrderBy({"nom_group" = "ASC"})
    */
    private $idgroup;

  /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
     /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

  
   
    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $iduser;

     /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="id_userparrain", referencedColumnName="id")
     */
    private $iduserParrain;
    
   /**
     * @orm\Column(type="boolean", name="is_accepted",nullable=true))
     */
    private $isaccepted;

     /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    
    public function __toString() {
        return $this->getName();    // this will not look good if SonataAdminBundle uses this ;)
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
     * Set infos
     *
     * @param string $description
     * @return ChronoUsergroup
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get infos
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
          $this->isaccepted = false; // Default value for column is_visible
             $this->setCreatedAt(new \DateTime());
      
      //  $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
  


    /**
    

    /**
     * Set iaccepted
     *
     * @param boolean $iaccepted
     * @return DemandeUsergroup
     */
    public function setIsaccepted($isaccepted)
    {
        $this->isaccepted = $isaccepted;
    
        return $this;
    }

    /**
     * Get iaccepted
     *
     * @return boolean 
     */
    public function getIsaccepted()
    {
        return $this->isaccepted;
    }

    /**
     * Set idgroup
     *
     * @param \Application\RelationsBundle\Entity\EserviceGroup $idgroup
     * @return DemandeUsergroup
     */
    public function setIdgroup(\Application\RelationsBundle\Entity\EserviceGroup $idgroup)
    {
        $this->idgroup = $idgroup;
    
        return $this;
    }

    /**
     * Get idgroup
     *
     * @return \Application\RelationsBundle\Entity\EserviceGroup 
     */
    public function getIdgroup()
    {
        return $this->idgroup;
    }

    /**
     * Set iduser
     *
     * @param \Application\Sonata\UserBundle\Entity\User $iduser
     * @return DemandeUsergroup
     */
    public function setIduser(\Application\Sonata\UserBundle\Entity\User $iduser = null)
    {
        $this->iduser = $iduser;
    
        return $this;
    }

    /**
     * Get iduser
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return DemandeUsergroup
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
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

      /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Epost
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set iduserParrain
     *
     * @param \Application\Sonata\UserBundle\Entity\User $iduserParrain
     * @return DemandeUsergroup
     */
    public function setIduserParrain(\Application\Sonata\UserBundle\Entity\User $iduserParrain = null)
    {
        $this->iduserParrain = $iduserParrain;

        return $this;
    }

    /**
     * Get iduserParrain
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getIduserParrain()
    {
        return $this->iduserParrain;
    }
}
