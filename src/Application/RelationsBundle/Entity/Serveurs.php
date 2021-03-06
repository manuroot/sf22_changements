<?php

namespace Application\RelationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * CertificatsProjet
 *
 * @ORM\Table(name="serveurs_main")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity(fields="nom", message="Nom déja utilisé")
 * @UniqueEntity(fields="ip_in", message="IP deja utilisée")
 * @UniqueEntity(fields="nom_dns", message="Nom DNS deja utilisée")
 * 
 * 
 * @ORM\Entity(repositoryClass="Application\RelationsBundle\Repository\ServeursRepository")
 */
class Serveurs {

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
     * @ORM\Column(name="nom", type="string", length=40, nullable=false,unique=true)
     */
    private $nom;
  /**
     * @var string
     *
     * @ORM\Column(name="nom_dns", type="string", length=80, nullable=false,unique=true)
     */
    private $nom_dns;


  
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;

     /**
     * @var string $ip_in
     *
     * @ORM\Column(name="ip_in", type="string", length=20, nullable=true, unique=true)
      * 
      * 
     *
     * @Assert\Regex(
     *     pattern="/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/",
     *     match=true,
     *     message="patterns: ip ex: 192.168.1.12"
     * )
     */
  
    private $ip_in;
    
    /**
     * @var string $ip_out
     *
     * @ORM\Column(name="ip_out", type="string", length=20, nullable=true)
     * @Assert\Regex(
     *     pattern="/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/",
     *     match=true,
     *     message="patterns: ip ex: 192.168.1.12"
     * )
     */
     private $ip_out;

     /**
     * @var \ChronoUserGroup
     *
     * @ORM\ManyToOne(targetEntity="Environnements",inversedBy="serveurs",cascade={"persist", "merge"}))
     * @ORM\OrderBy({"nom" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_env", referencedColumnName="id",nullable=true)
     * })
     */
    private $id_env;
    
  
    
      /**
     * @var \ServeursSites
     *
     * @ORM\ManyToOne(targetEntity="ServeursSites",inversedBy="serveurs", cascade={"persist", "merge"}))
     * @ORM\OrderBy({"nom" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_site", referencedColumnName="id",nullable=true)
     * })
     */
    private $idsite;
    
        /**
     * @var \ServeursZones
     *
     * @ORM\ManyToOne(targetEntity="ServeursZones",inversedBy="serveurs", cascade={"persist", "merge"}))
     * @ORM\OrderBy({"nom" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_zone", referencedColumnName="id",nullable=true)
     * })
     */
    private $idzone;
    
    
       /**
     * @var boolean
     *
     * @ORM\Column(name="warning", type="boolean", nullable=true)
     */
    private $warning;
    
      /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_date", type="date", nullable=false)
     */
    private $addedDate;

      /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="date", nullable=false)
     */
    private $updatedDate;  
    
    
     /**
     * @var \Projet
     *
     * @ORM\ManyToMany(targetEntity="Application\RelationsBundle\Entity\Projet")
     * @ORM\JoinTable(name="serveurs_projets",
     *      joinColumns={@ORM\JoinColumn(name="id_serveur", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_projet", referencedColumnName="id")}
     *      )
     */
    private $idProjet;
    
    
      public function __construct()
  {
    $this->addedDate = new \DateTime('now');
    $this->updatedDate = new \DateTime('now');
    $this->idProjet = new ArrayCollection();
    $this->warning = false;
    
  }
    
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
     * @return Serveurs
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
     * Set nom
     *
     * @param string $nom
     * @return Serveurs
     */
    public function setNomDns($nom_dns)
    {
        $this->nom_dns = $nom_dns;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNomDns()
    {
        return $this->nom_dns;
    }
    
    
 

  
    /**
     * Set description
     *
     * @param string $description
     * @return Serveurs
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
     * Set ip_in
     *
     * @param string $ipIn
     * @return Serveurs
     */
    public function setIpIn($ipIn)
    {
        $this->ip_in = $ipIn;
    
        return $this;
    }

    /**
     * Get ip_in
     *
     * @return string 
     */
    public function getIpIn()
    {
        return $this->ip_in;
    }

    /**
     * Set ip_out
     *
     * @param string $ipOut
     * @return Serveurs
     */
    public function setIpOut($ipOut)
    {
        $this->ip_out = $ipOut;
    
        return $this;
    }

    /**
     * Get ip_out
     *
     * @return string 
     */
    public function getIpOut()
    {
        return $this->ip_out;
    }

  

    /**
     * Set idsite
     *
     * @param \Application\RelationsBundle\Entity\ServeursSites $idsite
     * @return Serveurs
     */
    public function setIdsite(\Application\RelationsBundle\Entity\ServeursSites $idsite = null)
    {
        $this->idsite = $idsite;
    
        return $this;
    }

    /**
     * Get idsite
     *
     * @return \Application\RelationsBundle\Entity\ServeursSites 
     */
    public function getIdsite()
    {
        return $this->idsite;
    }

    /**
     * Set idzone
     *
     * @param \Application\RelationsBundle\Entity\ServeursZones $idzone
     * @return Serveurs
     */
    public function setIdzone(\Application\RelationsBundle\Entity\ServeursZones $idzone = null)
    {
        $this->idzone = $idzone;
    
        return $this;
    }

    /**
     * Get idzone
     *
     * @return \Application\RelationsBundle\Entity\ServeursZones 
     */
    public function getIdzone()
    {
        return $this->idzone;
    }

  

    /**
     * Set id_env
     *
     * @param \Application\RelationsBundle\Entity\Environnements $idEnv
     * @return Serveurs
     */
    public function setIdEnv(\Application\RelationsBundle\Entity\Environnements $idEnv = null)
    {
        $this->id_env = $idEnv;
    
        return $this;
    }

    /**
     * Get id_env
     *
     * @return \Application\RelationsBundle\Entity\Environnements 
     */
    public function getIdEnv()
    {
        return $this->id_env;
    }
    
     /**
     * Set addedDate
     *
     * @param \DateTime $addedDate
     * @return CertificatsCenter
     */
    public function setAddedDate($addedDate)
    {
        $this->addedDate = $addedDate;
    
        return $this;
    }

    /**
     * Get addedDate
     *
     * @return \DateTime 
     */
    public function getAddedDate()
    {
        return $this->addedDate;
    }

    /**
     * Set addedDate
     *
     * @param \DateTime $addedDate
     * @return CertificatsCenter
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    
        return $this;
    }

    /**
     * Get addedDate
     *
     * @return \DateTime 
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }
    
      /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue() {
       
        $this->setUpdatedDate(new \DateTime());
     
    }

    public function prePersist() {
          $this->setAddedDate(new \DateTime);
        $this->setUpdatedDate(new \DateTime);
        /* if (null == $this->getGlobalnote()){


          } */
    }
     /**
    *  Set statusFile
     *
     * @param boolean $statusFile
     * @return CertificatsCenter
     */
    public function setWarning($warning)
    {
        $this->warning = $warning;
    
        return $this;
    }

    /**
     * Get warning
     *
     * @return boolean 
     */
    public function getWarning()
    {
        return $this->warning;
    }
    
    /**
     *  Add IdProjet
     *
     * @param \Application\RelationsBundle\Entity\Serveurs $idProjet
     * @return Serveur
     */
    public function addIdProjet(\Application\RelationsBundle\Entity\Environnements $idProjet) {
        $this->idProjet[] = $idProjet;

        return $this;
    }

    /**
     * Remove IdProjet
     *
     * @param \Application\RelationsBundle\Entity\Serveurs $IdProjet
     */
    public function removeIdProjet(\Application\RelationsBundle\Entity\Environnements $idProjet) {
        $this->idProjet->removeElement($idProjet);
    }

    /**
     * Get IdProjet
     *
     * @return \Doctrine\Common\Collections\Collection 

     */
    public function getIdProjet() {
        return $this->idProjet;
    }
}