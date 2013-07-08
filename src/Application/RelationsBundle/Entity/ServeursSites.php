<?php

namespace Application\RelationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use APY\DataGridBundle\Grid\Mapping as GRID;



/**
 * ServeursSites
 *
 * @ORM\Table(name="serveurs_sites")
 * @ORM\Entity
 * @UniqueEntity(fields="nom", message="Nom déja utilisé")
 * @UniqueEntity(fields="ip", message="IP deja utilisée")
 * @ORM\Entity(repositoryClass="Application\RelationsBundle\Repository\ServeursSitesRepository")
 * @GRID\Source(columns="id,nom,ip,description") 
 */
class ServeursSites {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @GRID\Column(title="id", size="20", type="text",filter="false")
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
      /**
     * @var ArrayCollection $users
     *
     * @ORM\OneToMany(targetEntity="Serveurs", mappedBy="idsite", cascade={"persist"})
     */
    private $serveurs;
 
     /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=20, nullable=true, unique=true)
     * 
     * @Assert\Regex(
     *     pattern="/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/",
     *     match=true,
     *     message="patterns: ip ex: 192.168.1.12"
     * )
     */
  
    private $ip;
    
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
     * Constructor
     */
    public function __construct()
    {
        $this->serveurs = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add serveurs
     *
     * @param \Application\RelationsBundle\Entity\Serveurs $serveurs
     * @return ServeursSites
     */
    public function addServeur(\Application\RelationsBundle\Entity\Serveurs $serveurs)
    {
        $this->serveurs[] = $serveurs;
    
        return $this;
    }

    /**
     * Remove serveurs
     *
     * @param \Application\RelationsBundle\Entity\Serveurs $serveurs
     */
    public function removeServeur(\Application\RelationsBundle\Entity\Serveurs $serveurs)
    {
        $this->serveurs->removeElement($serveurs);
    }

    /**
     * Get serveurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServeurs()
    {
        return $this->serveurs;
    }
    
        /**
     * Set description
     *
     * @param string $description
     * @return Changements
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
     * Set ip
     *
     * @param string $ip
     * @return string
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

}