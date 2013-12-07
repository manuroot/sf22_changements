<?php


namespace Application\CalendarBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Eservicegroup
 *
 * @ORM\Table(name="wdcalendar_group")
 * @ORM\Entity(repositoryClass="Application\CalendarBundle\Repository\CalendarGroupRepository")
 * @UniqueEntity(fields="nomGroup", message="Ce nom de groupe existe déjà...")
 */
class CalendarGroup
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
     * @ORM\Column(name="nom_group", type="string", length=40, nullable=false)
     */
    private $nomGroup;

  /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=50, nullable=true)
     */
    private $description;
    
     /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=40, nullable=true)
     /**
     * @Assert\Email(
     *     message = "'{{ value }}' n'est pas un email valide.",
     *     checkMX = false
     * )
     */
       private $email;
       
  
    
     
     /**
     * @var String
     *
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User",cascade={"persist"})
     * @ORM\JoinTable(name="wdcalendar_allusers",
     *      joinColumns={@ORM\JoinColumn(name="egroup_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    private $users;
    
    /**
     * @var \Owner
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_owner", referencedColumnName="id",nullable=false)
     * })
     */
    private $owner;
   
 
    
    public function __toString() {
        return $this->getNomGroup();    // this will not look good if SonataAdminBundle uses this ;)
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
     * Set nomGroup
     *
     * @param string $nomGroup
     * @return User
     */
    public function setNomGroup($nomGroup)
    {
        $this->nomGroup = $nomGroup;
    
        return $this;
    }

    /**
     * Get nomGroup
     *
     * @return string 
     */
    public function getNomGroup()
    {
        return $this->nomGroup;
    }
    
    
      /**
     * Set infos
     *
     * @param string $description
     * @return User
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
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
         $this->ishared = false;
    }
    
  

    /**
     * Add users
     *
     * @param \Application\Sonata\UserBundle\Entity\User $users
     * @return User
     */
    public function addUser(\Application\Sonata\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \Application\Sonata\UserBundle\Entity\User $users
     */
    public function removeUser(\Application\Sonata\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set owner
     *
     * @param \Application\Sonata\UserBundle\Entity\User $owner
     * @return CalendarGroup
     */
    public function setOwner(\Application\Sonata\UserBundle\Entity\User $owner)
    {
        $this->owner = $owner;
    
        return $this;
    }

    /**
     * Get owner
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }

   
}