<?php

namespace Application\ChangementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Projet
 *
 * @ORM\Table(name="changement_status")
 * @ORM\Entity
 */
class ChangementStatus
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
     * Set nomProjet
     *
     * @param string $nom
     * @return Changement_status
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
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
     * @return Applis
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