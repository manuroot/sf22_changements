<?php

namespace Application\RelationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CertificatsProjet
 *
 * @ORM\Table(name="serveurs_zones")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\RelationsBundle\Repository\ServeursZonesRepository")
 */
class ServeursZones {

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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
        /**
     * @var ArrayCollection $serveurs
     *
     * @ORM\OneToMany(targetEntity="Serveurs", mappedBy="idzone", cascade={"persist"})
     */
    private $serveurs;
 
      
    
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
     * @return ServeursZones
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
}