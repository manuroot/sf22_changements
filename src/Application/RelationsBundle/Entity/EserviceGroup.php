<?php


namespace Application\RelationsBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Eservicegroup
 *
 * @ORM\Table(name="eservices_group")
 * @ORM\Entity(repositoryClass="Application\RelationsBundle\Repository\EserviceGroupRepository")
 * @UniqueEntity(fields="nomGroup", message="Ce nom de groupe existe déjà...")
 * @ORM\Entity
 */
class EserviceGroup
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
     * @var ArrayCollection $users
     *
     * @ORM\OneToMany(targetEntity="Application\Sonata\UserBundle\Entity\User", mappedBy="idgroup", cascade={"persist"})
     */
    private $users;
 
    
    
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
}