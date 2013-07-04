<?php

namespace Application\RelationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CertificatsProjet
 *
 * @ORM\Table(name="serveurs")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\RelationsBundle\Entity\ServeursRepository")
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
     * @ORM\Column(name="nom", type="string", length=40, nullable=false)
     */
    private $nom;
  /**
     * @var string
     *
     * @ORM\Column(name="nom_dns", type="string", length=80, nullable=false)
     */
    private $nom_dns;


    /**
     * @var string
     *
     * @ORM\Column(name="nom_site", type="string", length=100, nullable=false)
     */
    private $nom_site;

    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;

     /**
     * @var string
     *
     * @ORM\Column(name="ip_in", type="string", length=20, nullable=true)
     */
    private $ip_in;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ip_out", type="string", length=20, nullable=true)
     */
    private $ip_out;

     /**
     * @var string
     *
     * @ORM\Column(name="zone", type="string", length=50, nullable=true)
     */
    private $zone;
    
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
    
    
      public function setNomSite($nom_site)
    {
        $this->nom_site = $nom_site;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNomSite()
    {
        return $this->nom_site;
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
     * Set zone
     *
     * @param string $zone
     * @return Serveurs
     */
    public function setZone($zone)
    {
        $this->zone = $zone;
    
        return $this;
    }

    /**
     * Get zone
     *
     * @return string 
     */
    public function getZone()
    {
        return $this->zone;
    }
}